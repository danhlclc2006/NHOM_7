<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Models\Tour;

class DashboardController extends AdminBaseController
{
    public function __construct()
    {
        Auth::requireRole(['admin']);
    }

    public function index()
    {
        $db = Database::getConnection();
        $tourModel = new Tour();

        $today = date('Y-m-d');
        $thisMonth = date('Y-m') . '%'; // Dùng cho LIKE '2023-11%'

        $toursCount = $tourModel->countAll();

        $sqlToday = "SELECT COUNT(*) as count FROM bookings WHERE CAST(created_at AS DATE) = :today";
        $stmtToday = $db->prepare($sqlToday);
        $stmtToday->execute(['today' => $today]);
        $todayBookings = $stmtToday->fetch()['count'];

        $sqlRevenue = "SELECT SUM(total_price) as total FROM bookings 
                       WHERE created_at LIKE :month 
                       AND status != 'Hủy'";
        $stmtRev = $db->prepare($sqlRevenue);
        $stmtRev->execute(['month' => $thisMonth]);
        $monthRevenue = $stmtRev->fetch()['total'] ?? 0;

        $sqlActive = "SELECT COUNT(*) as count FROM tour_departures 
                      WHERE start_date <= :d1 AND end_date >= :d2 
                      AND status != 'Hủy'";
        $stmtActive = $db->prepare($sqlActive);
        $stmtActive->execute(['d1' => $today, 'd2' => $today]);
        $activeDepartures = $stmtActive->fetch()['count'];

        // Đóng gói số liệu thống kê
        $stats = [
            'tours_count'       => $toursCount,
            'today_bookings'    => $todayBookings,
            'month_revenue'     => $monthRevenue,
            'active_departures' => $activeDepartures
        ];

        // Dùng subquery để lấy tên khách & sđt từ bảng customers_in_booking (lấy người đầu tiên của booking đó)
        $sqlRecent = "SELECT b.*, t.tour_name, 
                      (SELECT full_name FROM customers_in_booking WHERE booking_id = b.booking_id ORDER BY customer_id ASC LIMIT 1) as contact_name,
                      (SELECT phone FROM customers_in_booking WHERE booking_id = b.booking_id ORDER BY customer_id ASC LIMIT 1) as phone
                      FROM bookings b 
                      JOIN tours t ON b.tour_id = t.tour_id 
                      ORDER BY b.created_at DESC LIMIT 5";
        $recentBookings = $db->query($sqlRecent)->fetchAll();

        // --- 3. LẤY TOUR SẮP KHỞI HÀNH (5 lịch sắp tới) ---
        // Lấy các lịch có ngày đi >= hôm nay
        $sqlUpcoming = "SELECT d.*, t.tour_name, t.tour_type, 
                        (SELECT COUNT(*) FROM bookings b WHERE b.departure_id = d.departure_id AND b.status != 'Hủy') as joined_count
                        FROM tour_departures d 
                        JOIN tours t ON d.tour_id = t.tour_id 
                        WHERE d.start_date >= :today AND d.status != 'Hủy'
                        ORDER BY d.start_date ASC LIMIT 5";
        $stmtUpcoming = $db->prepare($sqlUpcoming);
        $stmtUpcoming->execute(['today' => $today]);
        $upcomingTours = $stmtUpcoming->fetchAll();

        // --- 4. HOẠT ĐỘNG HỆ THỐNG (LOGS - Nếu có bảng logs) ---
        // Nếu chưa có bảng logs hoặc không dùng, để mảng rỗng
        $logs = [];
        try {
            $sqlLogs = "SELECT l.*, t.tour_name as title 
                        FROM tour_logs l 
                        JOIN tour_assignments ta ON l.assign_id = ta.assign_id
                        JOIN tours t ON ta.tour_id = t.tour_id
                        ORDER BY l.created_at DESC LIMIT 5";
            // Kiểm tra xem bảng có tồn tại không trước khi query để tránh lỗi
            $logs = $db->query($sqlLogs)->fetchAll();
        } catch (\Exception $e) {
            $logs = [];
        }

        $this->view('admin/dashboard', [
            'stats'          => $stats,
            'recentBookings' => $recentBookings,
            'upcomingTours'  => $upcomingTours,
            'logs'           => $logs
        ]);
    }
}
