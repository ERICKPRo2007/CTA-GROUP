<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login (1).html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CTA Grupo - Dashboard</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }

    :root {
      --bg: #0d1117;
      --sidebar: #161b22;
      --card: #1c2128;
      --border: rgba(255,255,255,0.08);
      --red: #e53935;
      --blue: #1a6cf5;
      --green: #2ea043;
      --yellow: #f0a500;
      --text: #e6edf3;
      --muted: rgba(255,255,255,0.45);
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      min-height: 100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      width: 240px;
      background: var(--sidebar);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      z-index: 200;
      transition: transform 0.3s ease;
    }

    .sidebar-logo {
      padding: 20px 16px;
      border-bottom: 1px solid var(--border);
      text-align: center;
    }

    .sidebar-logo img {
      width: 70px;
      border-radius: 8px;
      margin-bottom: 6px;
    }

    .sidebar-logo h2 {
      font-size: 0.9rem;
      font-weight: 700;
      color: var(--red);
      letter-spacing: 1px;
    }

    .sidebar-logo p {
      font-size: 0.62rem;
      color: var(--muted);
      margin-top: 2px;
    }

    .sidebar-menu {
      flex: 1;
      padding: 12px 0;
      overflow-y: auto;
    }

    .menu-label {
      font-size: 0.62rem;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 10px 16px 4px;
    }

    .menu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      color: var(--muted);
      text-decoration: none;
      font-size: 0.86rem;
      transition: all 0.2s;
      cursor: pointer;
      border-left: 3px solid transparent;
    }

    .menu-item:hover, .menu-item.active {
      color: var(--text);
      background: rgba(255,255,255,0.05);
      border-left-color: var(--red);
    }

    .menu-item .icon { font-size: 1rem; width: 20px; text-align: center; }

    .sidebar-footer {
      padding: 14px 16px;
      border-top: 1px solid var(--border);
      font-size: 0.78rem;
      color: var(--muted);
    }

    .sidebar-footer strong { color: var(--text); display: block; margin-bottom: 8px; }

    .btn-logout {
      display: block; width: 100%; padding: 8px;
      background: rgba(229,57,53,0.15);
      border: 1px solid rgba(229,57,53,0.3);
      border-radius: 6px; color: var(--red);
      text-align: center; text-decoration: none;
      font-size: 0.8rem; font-weight: 600;
      transition: background 0.2s;
    }
    .btn-logout:hover { background: rgba(229,57,53,0.3); }

    /* ── OVERLAY móvil ── */
    .overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.6);
      z-index: 150;
    }
    .overlay.show { display: block; }

    /* ── MAIN ── */
    .main {
      margin-left: 240px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .topbar {
      background: var(--sidebar);
      border-bottom: 1px solid var(--border);
      padding: 14px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .topbar-left { display: flex; align-items: center; gap: 12px; }
    .topbar h1 { font-size: 1rem; font-weight: 700; }
    .topbar span { font-size: 0.75rem; color: var(--muted); }

    /* Botón hamburguesa — solo móvil */
    .hamburger {
      display: none;
      background: none;
      border: none;
      color: var(--text);
      font-size: 1.4rem;
      cursor: pointer;
      padding: 4px;
    }

    .content { padding: 20px; flex: 1; }

    /* ── STATS CARDS ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 20px;
    }

    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 16px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .stat-icon {
      width: 44px; height: 44px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem;
      flex-shrink: 0;
    }

    .stat-card .info p { font-size: 0.72rem; color: var(--muted); margin-bottom: 3px; }
    .stat-card .info h3 { font-size: 1.5rem; font-weight: 700; }
    .stat-card .info small { font-size: 0.7rem; }

    /* ── TABLES ── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; }

    .panel {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
    }

    .panel-header {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }

    .panel-header h3 { font-size: 0.88rem; font-weight: 600; }

    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 400px; }
    th { padding: 9px 14px; font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap; }
    td { padding: 11px 14px; font-size: 0.82rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: rgba(255,255,255,0.02); }

    .badge {
      display: inline-block; padding: 3px 9px;
      border-radius: 20px; font-size: 0.7rem; font-weight: 600;
      white-space: nowrap;
    }
    .badge-red    { background: rgba(229,57,53,0.15);  color: #ef5350; }
    .badge-yellow { background: rgba(240,165,0,0.15);  color: #f0a500; }
    .badge-green  { background: rgba(46,160,67,0.15);  color: #3fb950; }
    .badge-blue   { background: rgba(26,108,245,0.15); color: #58a6ff; }

    .section { display: none; }
    .section.active { display: block; }

    .section-title {
      font-size: 1rem; font-weight: 700;
      margin-bottom: 16px;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--border);
    }

    .btn {
      display: inline-block; padding: 8px 16px;
      border-radius: 7px; font-size: 0.82rem; font-weight: 600;
      border: none; cursor: pointer; transition: all 0.2s;
    }
    .btn-primary { background: var(--blue); color: #fff; }
    .btn-primary:hover { background: #2979ff; }

    .full-panel { background: var(--card); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }

    /* ══════════════════════════════
       RESPONSIVE — TABLET
    ══════════════════════════════ */
    @media (max-width: 1024px) {
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
      .grid-2 { grid-template-columns: 1fr; }
    }

    /* ══════════════════════════════
       RESPONSIVE — MÓVIL
    ══════════════════════════════ */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.open {
        transform: translateX(0);
      }
      .main {
        margin-left: 0;
      }
      .hamburger {
        display: block;
      }
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }
      .stat-card {
        flex-direction: column;
        text-align: center;
        padding: 14px 10px;
        gap: 8px;
      }
      .stat-icon { width: 38px; height: 38px; font-size: 1.1rem; }
      .stat-card .info h3 { font-size: 1.3rem; }
      .content { padding: 14px; }
      .topbar { padding: 12px 14px; }
      .topbar span { display: none; }
    }

    @media (max-width: 400px) {
      .stats-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
    }
  </style>
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- ── SIDEBAR ── -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <img src="grupo1.png.png" alt="CTA Logo">
    <h2>CTA GRUPO</h2>
    <p>Consultoría Tecnológica Avanzada</p>
  </div>

  <nav class="sidebar-menu">
    <div class="menu-label">Principal</div>
    <a class="menu-item active" onclick="showSection('dashboard', this)">
      <span class="icon">📊</span> Dashboard
    </a>
    <div class="menu-label">Gestión</div>
    <a class="menu-item" onclick="showSection('tickets', this)">
      <span class="icon">🎫</span> Tickets
    </a>
    <a class="menu-item" onclick="showSection('clientes', this)">
      <span class="icon">👥</span> Clientes
    </a>
    <a class="menu-item" onclick="showSection('tecnicos', this)">
      <span class="icon">👨‍💻</span> Técnicos
    </a>
    <a class="menu-item" onclick="showSection('inventario', this)">
      <span class="icon">📦</span> Inventario
    </a>
    <div class="menu-label">Sistema</div>
    <a class="menu-item" onclick="showSection('reportes', this)">
      <span class="icon">📈</span> Reportes
    </a>
    <a class="menu-item" onclick="showSection('usuarios', this)">
      <span class="icon">⚙️</span> Usuarios
    </a>
  </nav>

  <div class="sidebar-footer">
    <strong>👤 <?php echo htmlspecialchars($_SESSION["usuario"]); ?></strong>
    <a href="cerrar_sesion.php" class="btn-logout">🚪 Cerrar Sesión</a>
  </div>
</aside>

<!-- ── MAIN ── -->
<main class="main">
  <div class="topbar">
    <div class="topbar-left">
      <button class="hamburger" onclick="toggleSidebar()">☰</button>
      <h1 id="page-title">📊 Dashboard</h1>
    </div>
    <span id="fecha"></span>
  </div>

  <div class="content">

    <!-- DASHBOARD -->
    <div class="section active" id="sec-dashboard">
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(229,57,53,0.15)">🎫</div>
          <div class="info"><p>Tickets Abiertos</p><h3>24</h3><small style="color:#ef5350">↑ 3 nuevos hoy</small></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(46,160,67,0.15)">✅</div>
          <div class="info"><p>Resueltos</p><h3>187</h3><small style="color:#3fb950">Este mes</small></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(26,108,245,0.15)">👥</div>
          <div class="info"><p>Clientes</p><h3>43</h3><small style="color:#58a6ff">Activos</small></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(240,165,0,0.15)">👨‍💻</div>
          <div class="info"><p>Técnicos</p><h3>8</h3><small style="color:#f0a500">6 en campo</small></div>
        </div>
      </div>

      <div class="grid-2">
        <div class="panel">
          <div class="panel-header"><h3>🎫 Tickets Recientes</h3></div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>#</th><th>Cliente</th><th>Asunto</th><th>Estado</th></tr></thead>
              <tbody>
                <tr><td>#1045</td><td>FRESCO DEL HORNO (FDH)</td><td>PC no enciende</td><td><span class="badge badge-red">Abierto</span></td></tr>
                <tr><td>#1044</td><td>CLISID</td><td>Red caída</td><td><span class="badge badge-yellow">En proceso</span></td></tr>
                <tr><td>#1043</td><td>ESCOTTO BOURNIGAL</td><td>Impresora</td><td><span class="badge badge-green">Resuelto</span></td></tr>
                <tr><td>#1042</td><td>ROJO GAS (HERRERA)</td><td>Virus/Malware</td><td><span class="badge badge-yellow">En proceso</span></td></tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="panel">
          <div class="panel-header"><h3>👨‍💻 Técnicos</h3></div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Técnico</th><th>Cliente</th><th>Estado</th></tr></thead>
              <tbody>
                <tr><td>Franckkelly (Soporte)</td><td>ROJO GAS (HERRERA)</td><td><span class="badge badge-yellow">En campo</span></td></tr>
                <tr><td>Frangie Fermin(Soporte)</td><td>CLISID</td><td><span class="badge badge-yellow">En campo</span></td></tr>
                <tr><td>OMAR (Fundador)</td><td>—</td><td><span class="badge badge-green">Disponible</span></td></tr>
                <tr><td>Angel Santana (Fundador)</td><td>ESCOTTO BOURNIGAL</td><td><span class="badge badge-yellow">En campo</span></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- TICKETS -->
    <div class="section" id="sec-tickets">
      <div class="section-title">🎫 Gestión de Tickets</div>
      <div style="margin-bottom:14px"><button class="btn btn-primary">+ Nuevo Ticket</button></div>
      <div class="full-panel"><div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Fecha</th><th>Cliente</th><th>Asunto</th><th>Técnico</th><th>Prioridad</th><th>Estado</th></tr></thead>
          <tbody>
            <tr><td>#1045</td><td>20/05/26</td><td>ROJO GAS (HERRERA)</td><td>PC no enciende</td><td>Juan Pérez</td><td><span class="badge badge-red">Alta</span></td><td><span class="badge badge-red">Abierto</span></td></tr>
            <tr><td>#1044</td><td>20/05/26</td><td>CLISID</td><td>Red caída</td><td>María López</td><td><span class="badge badge-red">Alta</span></td><td><span class="badge badge-yellow">En proceso</span></td></tr>
            <tr><td>#1043</td><td>19/05/26</td><td>ESCOTTO BOURNIGAL</td><td>Impresora</td><td>Carlos Díaz</td><td><span class="badge badge-yellow">Media</span></td><td><span class="badge badge-green">Resuelto</span></td></tr>
            <tr><td>#1042</td><td>19/05/26</td><td>FRESCO DEL HORNO (FDH)</td><td>Virus/Malware</td><td>Ana Martínez</td><td><span class="badge badge-red">Alta</span></td><td><span class="badge badge-yellow">En proceso</span></td></tr>
          </tbody>
        </table>
      </div></div>
    </div>

    <!-- CLIENTES -->
    <div class="section" id="sec-clientes">
      <div class="section-title">👥 Gestión de Clientes</div>
      <div style="margin-bottom:14px"><button class="btn btn-primary">+ Nuevo Cliente</button></div>
      <div class="full-panel"><div class="table-wrap">
        <table>
          <thead><tr><th>Cliente</th><th>Contacto</th><th>Teléfono</th><th>Equipos</th><th>Tickets</th><th>Estado</th></tr></thead>
          <tbody>
            <tr><td>ROJO GAS (HERRERA)</td><td>Pedro Sánchez</td><td>809-000-0001</td><td>12</td><td><span class="badge badge-red">2</span></td><td><span class="badge badge-green">Activo</span></td></tr>
            <tr><td>CLISID</td><td>Rosa Méndez</td><td>809-000-0002</td><td>5</td><td><span class="badge badge-yellow">1</span></td><td><span class="badge badge-green">Activo</span></td></tr>
            <tr><td>ESCOTTO BOURNIGAL</td><td>Jorge Ruiz</td><td>809-000-0003</td><td>20</td><td><span class="badge badge-green">0</span></td><td><span class="badge badge-green">Activo</span></td></tr>
+             <tr><td>FRESCO DEL HORNO (FDH)</td><td>Dra. Carmen</td><td>809-000-0004</td><td>8</td><td><span class="badge badge-yellow">1</span></td><td><span class="badge badge-green">Activo</span></td></tr>
          </tbody>
        </table>
      </div></div>
    </div>

    <!-- TÉCNICOS -->
    <div class="section" id="sec-tecnicos">
      <div class="section-title">👨‍💻 Gestión de Técnicos</div>
      <div style="margin-bottom:14px"><button class="btn btn-primary">+ Nuevo Técnico</button></div>
      <div class="full-panel"><div class="table-wrap">
        <table>
          <thead><tr><th>Nombre</th><th>Especialidad</th><th>Teléfono</th><th>Tickets</th><th>Estado</th></tr></thead>
          <tbody>
            <tr><td>Juan Pérez</td><td>Redes y Hardware</td><td>809-111-0001</td><td>3</td><td><span class="badge badge-yellow">En campo</span></td></tr>
            <tr><td>María López</td><td>Software y SO</td><td>809-111-0002</td><td>2</td><td><span class="badge badge-yellow">En campo</span></td></tr>
            <tr><td>Carlos Díaz</td><td>Impresoras</td><td>809-111-0003</td><td>0</td><td><span class="badge badge-green">Disponible</span></td></tr>
            <tr><td>Ana Martínez</td><td>Seguridad</td><td>809-111-0004</td><td>1</td><td><span class="badge badge-yellow">En campo</span></td></tr>
          </tbody>
        </table>
      </div></div>
    </div>

    <!-- INVENTARIO -->
    <div class="section" id="sec-inventario">
      <div class="section-title">📦 Inventario de Equipos</div>
      <div style="margin-bottom:14px"><button class="btn btn-primary">+ Agregar Equipo</button></div>
      <div class="full-panel"><div class="table-wrap">
        <table>
          <thead><tr><th>Equipo</th><th>Marca/Modelo</th><th>Serie</th><th>Cliente</th><th>Condición</th></tr></thead>
          <tbody>
            <tr><td>💻 Laptop</td><td>Dell Latitude 5420</td><td>SN-001</td><td>ROJO GAS (HERRERA)</td><td><span class="badge badge-green">Bueno</span></td></tr>
            <tr><td>🖥️ Desktop</td><td>HP EliteDesk 800</td><td>SN-002</td><td>CLISID</td><td><span class="badge badge-yellow">Regular</span></td></tr>
            <tr><td>🖨️ Impresora</td><td>Epson L3150</td><td>SN-003</td><td>ESCOTTO BOURNIGAL</td><td><span class="badge badge-red">En reparación</span></td></tr>
            <tr><td>🌐 Router</td><td>MikroTik RB750</td><td>SN-004</td><td>FRESCO DEL HORNO (FDH)</td><td><span class="badge badge-green">Bueno</span></td></tr>
          </tbody>
        </table>
      </div></div>
    </div>

    <!-- REPORTES -->
    <div class="section" id="sec-reportes">
      <div class="section-title">📈 Reportes</div>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(46,160,67,0.15)">✅</div>
          <div class="info"><p>Resueltos</p><h3>187</h3><small style="color:#3fb950">Este mes</small></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(240,165,0,0.15)">⏱️</div>
          <div class="info"><p>Tiempo Promedio</p><h3>4.2h</h3><small style="color:#f0a500">Por ticket</small></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(26,108,245,0.15)">😊</div>
          <div class="info"><p>Satisfacción</p><h3>94%</h3><small style="color:#58a6ff">Clientes</small></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background:rgba(229,57,53,0.15)">🔴</div>
          <div class="info"><p>Pendientes</p><h3>24</h3><small style="color:#ef5350">Sin resolver</small></div>
        </div>
      </div>
    </div>

    <!-- USUARIOS -->
    <div class="section" id="sec-usuarios">
      <div class="section-title">⚙️ Usuarios del Sistema</div>
      <div style="margin-bottom:14px"><button class="btn btn-primary">+ Nuevo Usuario</button></div>
      <div class="full-panel"><div class="table-wrap">
        <table>
          <thead><tr><th>Usuario</th><th>Rol</th><th>Último Acceso</th><th>Estado</th></tr></thead>
          <tbody>
            <tr><td>admin</td><td><span class="badge badge-red">Administrador</span></td><td>Ahora</td><td><span class="badge badge-green">Activo</span></td></tr>
            <tr><td>juan</td><td><span class="badge badge-blue">Técnico</span></td><td>Hoy 08:30</td><td><span class="badge badge-green">Activo</span></td></tr>
            <tr><td>maria</td><td><span class="badge badge-blue">Técnico</span></td><td>Hoy 09:15</td><td><span class="badge badge-green">Activo</span></td></tr>
            <tr><td>supervisor</td><td><span class="badge badge-yellow">Supervisor</span></td><td>Ayer</td><td><span class="badge badge-green">Activo</span></td></tr>
          </tbody>
        </table>
      </div></div>
    </div>

  </div>
</main>

<script>
  // Fecha
  document.getElementById('fecha').textContent = new Date().toLocaleDateString('es-DO', {
    weekday:'long', year:'numeric', month:'long', day:'numeric'
  });

  // Navegación
  const titles = {
    dashboard:'📊 Dashboard', tickets:'🎫 Tickets', clientes:'👥 Clientes',
    tecnicos:'👨‍💻 Técnicos', inventario:'📦 Inventario',
    reportes:'📈 Reportes', usuarios:'⚙️ Usuarios'
  };

  function showSection(name, el) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
    document.getElementById('sec-' + name).classList.add('active');
    document.getElementById('page-title').textContent = titles[name];
    el.classList.add('active');
    closeSidebar();
  }

  // Sidebar móvil
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
  }

  function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('show');
  }
</script>

</body>
</html>
