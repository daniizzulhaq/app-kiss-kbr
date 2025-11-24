<!-- Alert Notifications dengan Animasi -->
<style>
    .custom-alert {
        position: relative;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        animation: slideInDown 0.4s ease-out;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .custom-alert .alert-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .custom-alert .alert-content {
        flex: 1;
    }

    .custom-alert .alert-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .custom-alert .alert-message {
        font-size: 0.9rem;
        opacity: 0.9;
        margin: 0;
    }

    .custom-alert .btn-close {
        flex-shrink: 0;
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .custom-alert .btn-close:hover {
        opacity: 1;
    }

    /* Success Alert */
    .alert-success-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .alert-success-custom .alert-icon {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Error Alert */
    .alert-error-custom {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .alert-error-custom .alert-icon {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Warning Alert */
    .alert-warning-custom {
        background: linear-gradient(135deg, #fad0c4 0%, #ff9a9e 100%);
        color: #333;
    }

    .alert-warning-custom .alert-icon {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Info Alert */
    .alert-info-custom {
        background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        color: #333;
    }

    .alert-info-custom .alert-icon {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Progress bar animation */
    .alert-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 0 0 12px 12px;
        animation: shrink 5s linear forwards;
    }

    @keyframes shrink {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    /* Alternative: Modern Flat Style */
    .alert-modern {
        border-left: 4px solid;
        background: white;
        color: #333;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .alert-modern.alert-success-modern {
        border-left-color: #10b981;
    }

    .alert-modern.alert-success-modern .alert-icon {
        background: #d1fae5;
        color: #10b981;
    }

    .alert-modern.alert-error-modern {
        border-left-color: #ef4444;
    }

    .alert-modern.alert-error-modern .alert-icon {
        background: #fee2e2;
        color: #ef4444;
    }

    .alert-modern.alert-warning-modern {
        border-left-color: #f59e0b;
    }

    .alert-modern.alert-warning-modern .alert-icon {
        background: #fef3c7;
        color: #f59e0b;
    }

    .alert-modern.alert-info-modern {
        border-left-color: #3b82f6;
    }

    .alert-modern.alert-info-modern .alert-icon {
        background: #dbeafe;
        color: #3b82f6;
    }

    /* Auto dismiss animation */
    .alert-dismissing {
        animation: slideOutUp 0.3s ease-in forwards;
    }

    @keyframes slideOutUp {
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
</style>

<!-- Style 1: Gradient dengan Progress Bar (Auto Dismiss) -->
@if(session('success'))
    <div class="custom-alert alert-success-custom alert-dismissible fade show" role="alert" id="successAlert">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Berhasil!</div>
            <div class="alert-message">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-progress"></div>
    </div>
@endif

@if(session('error'))
    <div class="custom-alert alert-error-custom alert-dismissible fade show" role="alert" id="errorAlert">
        <div class="alert-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Terjadi Kesalahan!</div>
            <div class="alert-message">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-progress"></div>
    </div>
@endif

@if(session('warning'))
    <div class="custom-alert alert-warning-custom alert-dismissible fade show" role="alert" id="warningAlert">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Perhatian!</div>
            <div class="alert-message">{{ session('warning') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-progress"></div>
    </div>
@endif

@if(session('info'))
    <div class="custom-alert alert-info-custom alert-dismissible fade show" role="alert" id="infoAlert">
        <div class="alert-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Informasi</div>
            <div class="alert-message">{{ session('info') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-progress"></div>
    </div>
@endif

<script>
    // Auto dismiss alert after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.custom-alert');
        
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('alert-dismissing');
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 300);
            }, 5000);
        });
    });
</script>

<!-- 
ALTERNATIF: Style 2 - Modern Flat (Tanpa Auto Dismiss)
Ganti kode di atas dengan ini jika prefer style flat modern:

@if(session('success'))
    <div class="custom-alert alert-modern alert-success-modern alert-dismissible fade show" role="alert">
        <div class="alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Berhasil!</div>
            <div class="alert-message">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="custom-alert alert-modern alert-error-modern alert-dismissible fade show" role="alert">
        <div class="alert-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Terjadi Kesalahan!</div>
            <div class="alert-message">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('warning'))
    <div class="custom-alert alert-modern alert-warning-modern alert-dismissible fade show" role="alert">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Perhatian!</div>
            <div class="alert-message">{{ session('warning') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('info'))
    <div class="custom-alert alert-modern alert-info-modern alert-dismissible fade show" role="alert">
        <div class="alert-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">Informasi</div>
            <div class="alert-message">{{ session('info') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
-->