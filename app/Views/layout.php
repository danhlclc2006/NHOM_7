<?php
$user = \App\Core\Auth::user();
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tour Management - Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        :root {
            --accent: #0f766e;
            --muted: #6b7280;
            --card-bg: #ffffff;
            --soft: #f7faf9;
            --max-width: 1200px;
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            color: #0f172a
        }

        .container {
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 24px
        }

        header.site-header {
            background: var(--card-bg);
            box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
            position: sticky;
            top: 0;
            z-index: 40
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 24px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .brand .logo {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), #67e8f9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700
        }

        nav.main-nav {
            display: flex;
            gap: 14px;
            align-items: center
        }

        nav.main-nav a {
            color: var(--muted);
            text-decoration: none;
            font-weight: 600
        }

        .cta {
            background: var(--accent);
            color: #fff;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: 0;
            font-size: 20px
        }

        .hero {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 30px;
            align-items: center;
            padding: 48px 0
        }

        .hero-left h1 {
            font-size: 34px;
            margin: 0 0 12px
        }

        .hero-left p {
            color: var(--muted);
            margin: 0 0 18px
        }

        .search-card {
            background: var(--card-bg);
            padding: 14px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(2, 6, 23, .06)
        }

        .search-row {
            display: flex;
            gap: 8px
        }

        .search-row input,
        .search-row select {
            flex: 1;
            padding: 10px;
            border: 1px solid #e6e9ee;
            border-radius: 8px
        }

        .search-row button {
            background: var(--accent);
            border: 0;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: 700
        }

        .hero-right {
            background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=60');
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            min-height: 260px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, .08)
        }

        section.grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 30px
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(2, 6, 23, .05)
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block
        }

        .card-body {
            padding: 12px
        }

        .card-title {
            font-weight: 700;
            margin: 0 0 6px
        }

        .card-meta {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 10px
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .price {
            font-weight: 800;
            color: var(--accent)
        }

        .btn-sm {
            padding: 8px 10px;
            border-radius: 8px;
            border: 0;
            background: #111827;
            color: #fff;
            font-weight: 700
        }

        .testimonials {
            display: flex;
            gap: 12px;
            margin-top: 30px
        }

        .testimonial {
            background: var(--soft);
            padding: 14px;
            border-radius: 10px;
            flex: 1
        }

        footer.site-footer {
            margin-top: 40px;
            background: #0b1220;
            color: #fff;
            padding: 30px;
            border-radius: 12px
        }

        footer .footer-inner {
            display: flex;
            flex-wrap: wrap;
            gap: 20px
        }

        footer a {
            color: inherit;
            text-decoration: none
        }

        @media (max-width:1000px) {
            .hero {
                grid-template-columns: 1fr
            }

            section.grid {
                grid-template-columns: repeat(2, 1fr)
            }

            nav.main-nav {
                display: none
            }

            .mobile-toggle {
                display: block
            }
        }

        @media (max-width:640px) {
            .container {
                padding: 14px
            }

            section.grid {
                grid-template-columns: 1fr
            }

            .header-inner {
                padding: 10px
            }

            .hero-left h1 {
                font-size: 26px
            }
        }

        .admin-badge {
            background: #fde68a;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 700;
            color: #92400e;
            font-size: 13px
        }
    </style>
</head>

<body>
    <main class="container">

        <?php
        if (isset($viewFile) && file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo '<p>View not found</p>';
        }
        ?>




    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        const toggle = document.querySelector('.mobile-toggle');
        const nav = document.querySelector('nav.main-nav');
        toggle && toggle.addEventListener('click', () => {
            if (nav.style.display === 'flex') nav.style.display = 'none';
            else nav.style.display = 'flex';
            nav.style.flexDirection = 'column';
            nav.style.background = 'white';
            nav.style.position = 'absolute';
            nav.style.right = '16px';
            nav.style.top = '68px';
            nav.style.padding = '12px';
            nav.style.borderRadius = '8px';
            nav.style.boxShadow = '0 8px 30px rgba(2,6,23,.08)';
        });
    </script>
</body>

</html>