<?php
// Incluir sistema de rutas dinámicas
if (!isset($base_path)) {
    require_once __DIR__ . '/rutas.php';
}

// Configuración ultra-optimizada del encabezado
$current_page = basename($_SERVER['PHP_SELF']);
$is_logged_in = isset($_SESSION['usuario_id']);

// Datos de navegación ultra-optimizados (estructura más eficiente)
$nav = [
    'inicio' => ['url' => 'index.php', 'text' => 'Inicio'],
    'nosotros' => [
        'items' => [
            ['url' => 'index.php#historia', 'text' => 'Historia'],
            ['url' => 'index.php#proposito', 'text' => 'Propósito']
        ]
    ],
    'proyectos' => [
        'items' => [
            ['url' => 'proyectos/proyecto.php?seccion=expo-tecnica', 'text' => 'Expotécnica'],
            ['url' => 'proyectos/proyecto.php', 'text' => 'Proyectos'],
            ['url' => 'proyectos/proyecto.php?seccion=destacados', 'text' => 'Destacados']
        ]
    ],
    'ciclos' => [
        'items' => [
            ['url' => 'institucional/ciclo_basico.php', 'text' => 'Ciclo Básico'],
            ['url' => 'institucional/ciclo_superior.php', 'text' => 'Ciclo Superior']
        ]
    ],
    'comunicados' => ['url' => 'institucional/comunicados.php', 'text' => 'Comunicados'],
    'preinscripciones' => ['url' => 'institucional/preinscripcion.php', 'text' => 'Preinscripciones']
];

// Función helper para generar enlaces
function navLink($url, $text, $isActive = false) {
    global $base_path;
    return sprintf(
        '<a href="%s%s" class="nav-link%s" role="menuitem"%s>%s</a>',
        $base_path,
        $url,
        $isActive ? ' active' : '',
        $isActive ? ' aria-current="page"' : '',
        htmlspecialchars($text)
    );
}

// Función helper para dropdowns
function dropdownMenu($key, $items) {
    $trigger = ucfirst($key);
    $output = sprintf(
        '<li class="dropdown" role="none">
            <span class="dropdown-trigger" role="menuitem" aria-haspopup="true" aria-expanded="false" tabindex="0">%s</span>
            <ul class="dropdown-menu" role="menu" aria-label="%s">',
        $trigger, $trigger
    );
    
    foreach ($items as $item) {
        $output .= sprintf(
            '<li role="none"><a href="%s%s" class="dropdown-link" role="menuitem">%s</a></li>',
            $GLOBALS['base_path'],
            $item['url'],
            htmlspecialchars($item['text'])
        );
    }
    
    return $output . '</ul></li>';
}
?>
<header role="banner">
    <div class="header-container">
        <div class="logo">
            <a href="<?php echo $base_path; ?>index.php" aria-label="Ir al inicio">
                <img src="<?php echo $base_path; ?>assets/img/logo/LOGO.png" 
                     alt="E.E.S.T. N° 14 - GONZÁLEZ CATÁN" 
                     class="logo-img"
                     width="120" 
                     height="60">
            </a>
        </div>
        
        <nav class="main-nav" role="navigation" aria-label="Navegación principal">
            <ul role="menubar">
                <li role="none"><?php echo navLink($nav['inicio']['url'], $nav['inicio']['text'], $current_page === 'index.php'); ?></li>
                <?php echo dropdownMenu('nosotros', $nav['nosotros']['items']); ?>
                <?php echo dropdownMenu('proyectos', $nav['proyectos']['items']); ?>
                <?php echo dropdownMenu('ciclos', $nav['ciclos']['items']); ?>
                <li role="none"><?php echo navLink($nav['comunicados']['url'], $nav['comunicados']['text'], $current_page === 'comunicados.php'); ?></li>
                <li role="none"><?php echo navLink($nav['preinscripciones']['url'], $nav['preinscripciones']['text'], $current_page === 'preinscripcion.php'); ?></li>
            </ul>
        </nav>
        
        <div class="user-actions" role="complementary" aria-label="Acciones de usuario">
            <?php if ($is_logged_in): ?>
                <a href="<?php echo $base_path; ?>panels/perfil.php" class="btn-perfil" aria-label="Ir a mi perfil">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>Perfil
                </a>
            <?php else: ?>
                <a href="<?php echo $base_path; ?>auth/registro.php" class="btn-registro<?php echo $current_page === 'registro.php' ? ' active' : ''; ?>" aria-label="Crear una cuenta">Registrarse</a>
                <a href="<?php echo $base_path; ?>auth/login.php" class="btn-login<?php echo $current_page === 'login.php' ? ' active' : ''; ?>" aria-label="Iniciar sesión">Iniciar sesión</a>
            <?php endif; ?>
        </div>
        
        <button class="mobile-menu-toggle" aria-label="Abrir menú de navegación" aria-expanded="false" type="button">
            <span class="hamburger-line"></span><span class="hamburger-line"></span><span class="hamburger-line"></span>
        </button>
    </div>
</header>

<script>
// HeaderManager ultra-optimizado
(function() {
    'use strict';
    
    class HeaderManager {
        constructor() {
            this.elements = {
                dropdowns: document.querySelectorAll('.dropdown'),
                mobileToggle: document.querySelector('.mobile-menu-toggle'),
                mainNav: document.querySelector('.main-nav')
            };
            this.isMobileMenuOpen = false;
            this.init();
        }
        
        init() {
            this.bindEvents();
            this.setupAccessibility();
        }
        
        bindEvents() {
            // Event delegation para dropdowns
            document.addEventListener('click', (e) => {
                const trigger = e.target.closest('.dropdown-trigger');
                const dropdown = e.target.closest('.dropdown');
                
                if (trigger) {
                    e.preventDefault();
                    this.toggleDropdown(dropdown);
                } else if (!dropdown) {
                    this.closeAllDropdowns();
                }
            });
            
            // Event delegation para teclado
            document.addEventListener('keydown', (e) => {
                if (e.target.classList.contains('dropdown-trigger') && (e.key === 'Enter' || e.key === ' ')) {
                    e.preventDefault();
                    this.toggleDropdown(e.target.closest('.dropdown'));
                }
            });
            
            // Menú móvil
            this.elements.mobileToggle?.addEventListener('click', () => this.toggleMobileMenu());
            
            // Responsive
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768 && this.isMobileMenuOpen) {
                    this.closeMobileMenu();
                }
            });
        }
        
        toggleDropdown(dropdown) {
            const isOpen = dropdown.classList.contains('active');
            this.closeAllDropdowns();
            if (!isOpen) {
                dropdown.classList.add('active');
                dropdown.querySelector('.dropdown-trigger').setAttribute('aria-expanded', 'true');
            }
        }
        
        closeAllDropdowns() {
            this.elements.dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
                dropdown.querySelector('.dropdown-trigger').setAttribute('aria-expanded', 'false');
            });
        }
        
        toggleMobileMenu() {
            this.isMobileMenuOpen ? this.closeMobileMenu() : this.openMobileMenu();
        }
        
        openMobileMenu() {
            this.isMobileMenuOpen = true;
            this.elements.mainNav.classList.add('mobile-open');
            this.elements.mobileToggle.classList.add('active');
            this.elements.mobileToggle.setAttribute('aria-expanded', 'true');
            this.elements.mobileToggle.setAttribute('aria-label', 'Cerrar menú de navegación');
        }
        
        closeMobileMenu() {
            this.isMobileMenuOpen = false;
            this.elements.mainNav.classList.remove('mobile-open');
            this.elements.mobileToggle.classList.remove('active');
            this.elements.mobileToggle.setAttribute('aria-expanded', 'false');
            this.elements.mobileToggle.setAttribute('aria-label', 'Abrir menú de navegación');
        }
        
        setupAccessibility() {
            document.querySelectorAll('.dropdown-trigger').forEach(trigger => {
                trigger.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const firstItem = trigger.parentElement.querySelector('.dropdown-menu a');
                        firstItem?.focus();
                    }
                });
            });
        }
    }
    
    // Inicialización optimizada
    (document.readyState === 'loading' ? 
        document.addEventListener('DOMContentLoaded', () => new HeaderManager()) : 
        new HeaderManager()
    );
})();
</script>
