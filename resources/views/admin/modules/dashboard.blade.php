@extends('admin.layout.layout')
@section('content')
    <div class="container-fluid p-4 dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-card mb-4">
            <div class="row g-0">
                <div class="col-md-8 p-4 d-flex flex-column">
                    <h2 class="welcome-title">Welcome back, Admin!</h2>
                    <p class="welcome-text">Here's what's happening with your platform today.</p>
                    <div class="mt-auto d-flex align-items-center">
                        <div class="pe-3 border-end">
                            <div class="text-muted small">TODAY'S DATE</div>
                            <div class="fw-bold">{{ now()->format('l, F j, Y') }}</div>
                        </div>
                        <div class="ps-3">
                            <div class="text-muted small">LAST LOGIN</div>
                            <div class="fw-bold">
                                {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'First login' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 welcome-bg d-none d-md-block">
                    <div class="welcome-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4">
            <!-- Total Users -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card user-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-subtitle">Total Users</h6>
                                <h2 class="card-count">{{ $count['user'] }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="stretched-link"></a>
                    </div>
                    <div class="card-footer bg-transparent">
                        <span>View all users <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Total Series -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card series-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-subtitle">Total Series</h6>
                                <h2 class="card-count">{{ $count['series'] }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-list-ol"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.series.index') }}" class="stretched-link"></a>
                    </div>
                    <div class="card-footer bg-transparent">
                        <span>View all series <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Total Videos -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card video-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-subtitle">Total Videos</h6>
                                <h2 class="card-count">{{ $count['videos'] }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.video.index') }}" class="stretched-link"></a>
                    </div>
                    <div class="card-footer bg-transparent">
                        <span>View all videos <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Series Videos -->
            <div class="col-xl-3 col-md-6">
                <div class="stat-card series-video-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-subtitle">Series Videos</h6>
                                <h2 class="card-count">{{ $count['series_videos'] }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-film"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.series.index') }}" class="stretched-link"></a>
                    </div>
                    <div class="card-footer bg-transparent">
                        <span>View series videos <i class="fas fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container {
            max-width: 1800px;
            margin: 0 auto;
        }

        .welcome-card {
            background: linear-gradient(135deg, #000000 0%, #bbc0ca 100%);
            color: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(106, 17, 203, 0.2);
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-text {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .welcome-bg {
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-icon i {
            font-size: 5rem;
            opacity: 0.3;
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card .card-body {
            padding-bottom: 0;
        }

        .stat-card .card-count {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 1rem 0;
        }

        .stat-card .card-subtitle {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            margin-bottom: 0;
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            opacity: 0.4;
            transform: scale(1.1);
        }

        .card-footer {
            border-top: 1px dashed rgba(0, 0, 0, 0.1);
            padding: 1rem;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .stat-card:hover .card-footer {
            background: rgba(0, 0, 0, 0.03) !important;
            color: #495057;
        }

        /* Card specific colors */
        .user-card {
            background-color: #f8f9fa;
        }

        .user-card .stat-icon {
            color: #4e73df;
        }

        .series-card {
            background-color: #fff8f1;
        }

        .series-card .stat-icon {
            color: #f6c23e;
        }

        .video-card {
            background-color: #f0f9f0;
        }

        .video-card .stat-icon {
            color: #1cc88a;
        }

        .series-video-card {
            background-color: #f0f7fa;
        }

        .series-video-card .stat-icon {
            color: #36b9cc;
        }
    </style>
@endsection
