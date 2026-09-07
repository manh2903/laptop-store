<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LaptopTF Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/style.css') }}" />
</head>
<style>
 /* CSS cho trang login */
        body {
            font-family: "Segoe UI", sans-serif;
            height: 100vh;
            background: #f0f2f5;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        /* Form Login */
        .login-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            padding: 40px;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 45px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        .logo h1 {
            font-size: 30px;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }

        button.login-btn {
            width: 100%;
            padding: 16px;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        button.login-btn:hover {
            background: #3552d8;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .register-prompt-box {
            text-align: center;
            font-size: 15px;
            color: #6c757d;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .register-link-footer {
            color: #4361ee;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .register-link-footer:hover {
            text-decoration: underline;
            color: #3552d8;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }
            .login-slider {
                height: 45vh;
            }
            .login-form {
                height: 55vh;
            }
        }
    </style>
<body>
    <div class="login-container">
        <div class="login-slider">
            <div class="slides">
                <div class="slide" style="background-image: url('{{ asset('backend/asset/images/tranglogin.jpg') }}');"></div>
                <div class="slide" style="background-image: url('{{ asset('backend/asset/images/iconlogin.jpg') }}');"></div>
                <div class="slide" style="background-image: url('{{ asset('backend/asset/images/iconlogin1.jpg') }}');"></div>
            </div>

            <div class="slider-nav">
                <button class="nav-arrow prev-btn"><i class="fas fa-chevron-left"></i></button>
                <div class="nav-bars">
                    <div class="bar active" data-slide="0"></div>
                    <div class="bar" data-slide="1"></div>
                    <div class="bar" data-slide="2"></div>
                </div>
                <button class="nav-arrow next-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="login-form">
            @yield('content')
        </div>

    </div>

    <script src="{{ asset('assets/js/main.js') }}"></script>
       @stack('scripts')
    
    <script>
        // JS cho hiệu ứng slider
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelector('.slides');
            const bars = document.querySelectorAll('.bar');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            const totalSlides = slides.children.length;
            let currentSlide = 0;

            const updateSlider = () => {
                slides.style.transform = `translateX(-${currentSlide * 100}%)`;
                bars.forEach((bar, index) => {
                    bar.classList.toggle('active', index === currentSlide);
                });
            };

            const nextSlide = () => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            };

            const prevSlide = () => {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSlider();
            };

            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);

            bars.forEach(bar => {
                bar.addEventListener('click', (e) => {
                    currentSlide = parseInt(e.target.dataset.slide);
                    updateSlider();
                });
            });

            // Tự động chuyển slide sau 5 giây
            setInterval(nextSlide, 5000);
        });
    </script>
</body>
</html>