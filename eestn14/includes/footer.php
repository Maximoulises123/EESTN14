<?php
// Incluir sistema de rutas dinámicas
if (!isset($base_path)) {
    require_once __DIR__ . '/rutas.php';
}

// Datos del footer optimizados
$footer_data = [
    'institucion' => [
        'titulo' => 'E.E.S.T. N°14',
        'descripcion' => 'Formando técnicos competentes y ciudadanos responsables desde 1980. Excelencia educativa para el futuro de Argentina.',
        'contacto' => [
            ['type' => 'address', 'content' => 'Bariloche 6615, B1758 González Catán, Provincia de Buenos Aires'],
            ['type' => 'phone', 'content' => '02202 42-8307', 'href' => 'tel:02202428307'],
            ['type' => 'email', 'content' => 'eest14platenza@abc.gob.ar', 'href' => 'mailto:eest14platenza@abc.gob.ar']
        ]
    ],
    'educacion' => [
        'titulo' => 'Educación',
        'links' => [
            ['url' => 'institucional/ciclo_basico.php', 'text' => 'Ciclo Básico'],
            ['url' => 'institucional/ciclo_superior.php', 'text' => 'Ciclo Superior'],
            ['url' => 'materias/materias_alimentos.php', 'text' => 'Tecnología en Alimentos'],
            ['url' => 'materias/materias_informatica.php', 'text' => 'Informática'],
            ['url' => 'materias/materias_programacion.php', 'text' => 'Programación']
        ]
    ],
    'institucional' => [
        'titulo' => 'Institucional',
        'links' => [
            ['url' => 'index.php#historia', 'text' => 'Historia'],
            ['url' => 'index.php#proposito', 'text' => 'Propósito'],
            ['url' => 'institucional/comunicados.php', 'text' => 'Comunicados'],
            ['url' => 'proyectos/capacidades.php', 'text' => 'Proyectos'],
            ['url' => 'institucional/preinscripcion.php', 'text' => 'Inscripciones']
        ]
    ]
];

// Función helper para generar enlaces
function footerLink($url, $text) {
    global $base_path;
    return sprintf('<a href="%s%s">%s</a>', $base_path, $url, htmlspecialchars($text));
}

// Función helper para generar listas
function footerList($links) {
    $output = '<ul>';
    foreach ($links as $link) {
        $output .= sprintf('<li>%s</li>', footerLink($link['url'], $link['text']));
    }
    return $output . '</ul>';
}

// Función helper para contacto
function contactItem($item) {
    if (isset($item['href'])) {
        return sprintf('<div class="contact-item"><a href="%s">%s</a></div>', $item['href'], htmlspecialchars($item['content']));
    }
    return sprintf('<div class="contact-item">%s</div>', htmlspecialchars($item['content']));
}
?>
<footer role="contentinfo">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-column">
                <h3><?php echo htmlspecialchars($footer_data['institucion']['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($footer_data['institucion']['descripcion']); ?></p>
                <div class="contact-info">
                    <?php foreach ($footer_data['institucion']['contacto'] as $item): ?>
                        <?php echo contactItem($item); ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="footer-column">
                <h3><?php echo htmlspecialchars($footer_data['educacion']['titulo']); ?></h3>
                <?php echo footerList($footer_data['educacion']['links']); ?>
            </div>
            
            <div class="footer-column">
                <h3><?php echo htmlspecialchars($footer_data['institucional']['titulo']); ?></h3>
                <?php echo footerList($footer_data['institucional']['links']); ?>
            </div>
            
            <div class="footer-column">
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d498.53141687234967!2d-58.61777885824825!3d-34.7831965766796!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc5b3899394c5%3A0x5e91bad5b50da11d!2sEEST%20N14!5e1!3m2!1ses!2sar!4v1757653371479!5m2!1ses!2sar" 
                        width="100%" 
                        height="200" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Ubicación de E.E.S.T. N°14 en Google Maps"
                        aria-label="Mapa interactivo de la ubicación de la escuela">
                    </iframe>
                </div>
            </div>
        </div>
        
        <div class="footer-separator"></div>
        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> E.E.S.T. N°14</p>
        </div>
    </div>
</footer>
