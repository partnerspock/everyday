<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio | Full Responsive Bento Journal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    [data-theme="dark"] {
      --bg: #0f1117;
      --surface: rgba(255, 255, 255, 0.05);
      --surface-hover: rgba(255, 255, 255, 0.09);
      --border: rgba(255, 255, 255, 0.14);
      --text: #f8fafc;
      --text-muted: #94a3b8;
      --nav-bg: rgba(15, 17, 23, 0.85);
      --glow-top-left: rgba(56, 189, 248, 0.15);
      --glow-bottom-right: rgba(192, 132, 252, 0.09);
      
      --neon-cyan: #38bdf8;
      --neon-purple: #c084fc;
      --neon-pink: #f472b6;
      --neon-amber: #fbbf24;
      --neon-emerald: #34d399;
    }

    [data-theme="light"] {
      --bg: #f1f5f9;
      --surface: #ffffff;
      --surface-hover: #ffffff;
      --border: rgba(15, 23, 42, 0.12);
      --text: #0f172a;
      --text-muted: #475569;
      --nav-bg: rgba(241, 245, 249, 0.85);
      --glow-top-left: rgba(2, 132, 199, 0.08);
      --glow-bottom-right: rgba(124, 58, 237, 0.05);
      
      --neon-cyan: #0284c7;
      --neon-purple: #7c3aed;
      --neon-pink: #db2777;
      --neon-amber: #d97706;
      --neon-emerald: #059669;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      background-color: var(--bg);
      color: var(--text);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    .bg-glow {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-image: 
        /* Upper Left Glow (Besar & Dominan) */
        radial-gradient(circle at 10% 15%, var(--glow-top-left, rgba(56, 189, 248, 0.12)) 0%, transparent 45%),
        /* Bottom Right Glow (Kecil & Lembut) */
        radial-gradient(circle at 90% 85%, var(--glow-bottom-right, rgba(192, 132, 252, 0.08)) 0%, transparent 40%);
      z-index: -1;
      pointer-events: none;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    /* Full Width Sticky Navigation Header */
    .nav {
      position: sticky;
      top: 0;
      width: 100%;
      background: var(--nav-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      z-index: 100;
      border-bottom: 1px solid var(--border);
    }

    .nav-container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 1.25rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .nav-logo {
      font-weight: 800;
      font-size: 1.25rem;
      letter-spacing: -0.02em;
      color: var(--text);
      text-decoration: none;
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 2rem;
    }

    .theme-toggle {
      background: var(--surface);
      border: 1px solid var(--border);
      color: var(--text);
      padding: 0.5rem 1rem;
      border-radius: 100px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .theme-toggle:hover {
      border-color: var(--neon-cyan);
      box-shadow: 0 0 10px rgba(56, 189, 248, 0.3);
    }

    /* Hero Section */
    .hero {
      min-height: 55vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 4rem 0 2rem 0;
    }

    .hero-badge {
      display: inline-block;
      padding: 0.4rem 1rem;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 100px;
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-bottom: 1.5rem;
      width: fit-content;
    }

    .hero h1 {
      font-size: clamp(2.8rem, 6vw, 5rem);
      font-weight: 800;
      line-height: 1.05;
      letter-spacing: -0.04em;
      margin-bottom: 1.5rem;
    }

    .hero p {
      font-size: 1.25rem;
      color: var(--text-muted);
      max-width: 650px;
    }

    /* Full Width Timeline Sub-Nav */
    .story-timeline-nav {
      position: sticky;
      top: 66px;
      width: 100%;
      background: var(--nav-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--border);
      z-index: 90;
      margin-bottom: 3rem;
    }

    .timeline-container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 1rem 2rem;
      display: flex;
      gap: 2rem;
      overflow-x: auto;
    }

    .timeline-link {
      color: var(--text-muted);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 700;
      white-space: nowrap;
      padding-bottom: 0.4rem;
      border-bottom: 2px solid transparent;
      transition: all 0.3s ease;
    }

    .timeline-link:hover {
      color: var(--text);
    }

    .timeline-link.active {
      color: var(--text);
      border-bottom-color: var(--neon-cyan);
    }

    /* Chapter Section */
    .chapter-section {
      margin-bottom: 7rem;
      scroll-margin-top: 150px;
    }

    .chapter-header {
      margin-bottom: 2.5rem;
    }

    .chapter-tag {
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.15em;
      margin-bottom: 0.5rem;
    }

    .chapter-title {
      font-size: 2.2rem;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    /* Bento Card & Glow Hover */
    .bento-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 2.2rem;
      backdrop-filter: blur(20px);
      display: flex;
      flex-direction: column;
      position: relative;
      overflow: hidden;
      text-decoration: none;
      color: inherit;
    }

    .bento-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 24px;
      padding: 1.5px;
      background: linear-gradient(135deg, transparent 30%, var(--glow-color, var(--neon-cyan)), transparent 70%);
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      opacity: 0;
      transition: opacity 0.4s ease;
      pointer-events: none;
    }

    .bento-card:hover {
      background: var(--surface-hover);
      box-shadow: 0 10px 30px -10px var(--glow-color, rgba(56, 189, 248, 0.3));
    }

    .bento-card:hover::before {
      opacity: 1;
    }

    .glow-cyan { --glow-color: var(--neon-cyan); }
    .glow-purple { --glow-color: var(--neon-purple); }
    .glow-pink { --glow-color: var(--neon-pink); }
    .glow-amber { --glow-color: var(--neon-amber); }
    .glow-emerald { --glow-color: var(--neon-emerald); }

    .card-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: var(--surface);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: -0.01em;
    }
    
    .card-title-secondary {
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: -0.01em;
      margin-bottom: 1rem;
    }

    .card-desc {
      color: var(--text-muted);
      font-size: 0.95rem;
      margin-bottom: 1rem;
      flex-grow: 1;
    }

    .card-meta {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
      margin-top: auto;
      align-items: center;
      margin-bottom: 1rem;
    }

    .tech-tag-link,
    .tech-tag {
      font-size: 0.75rem;
      padding: 0.3rem 0.8rem;
      border-radius: 100px;
      background: var(--surface);
      border: 1px solid var(--border);
      color: var(--text-muted);
      font-weight: 500;
      text-decoration: none;
    }

    .tech-tag-link:hover {
      color: var(--neon-emerald);
      border: 1px solid var(--neon-emerald);
      background: var(--surface-hover);
      box-shadow: 0 10px 30px -10px var(--neon-emerald);
    }

    .arrow-icon {
      position: absolute;
      top: 2rem;
      right: 2rem;
      color: var(--text-muted);
      transition: all 0.3s ease;
    }

    .bento-card:hover .arrow-icon {
      color: var(--text);
      transform: translate(3px, -3px);
    }

    /* Layout Variants */
    .grid-v1, .grid-v2 {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 1.5rem;
    }

    .grid-v3 {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 1.5rem;
      align-items: center;
    }

    /* IG Story Portrait Widget (9:16 Aspect Ratio) */
    .story-card-container {
      grid-column: span 5;
      display: flex;
      justify-content: center;
    }

    .ig-story-card {
      width: 100%;
      max-width: 320px;
      aspect-ratio: 9 / 16;
      border-radius: 28px;
      border: 1px solid var(--border);
      background: #000;
      position: relative;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0,0,0,0.5);
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ig-story-card:hover {
      transform: translateY(-6px) scale(1.02);
      border-color: var(--neon-pink);
      box-shadow: 0 20px 40px rgba(247, 37, 133, 0.3);
    }

    .ig-story-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.85;
      transition: opacity 0.3s ease;
    }

    .ig-story-card:hover .ig-story-img {
      opacity: 0.6;
    }

    .ig-story-header {
      position: absolute;
      top: 1rem;
      left: 1rem;
      right: 1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      z-index: 2;
    }

    .ig-story-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid var(--neon-pink);
    }

    .ig-story-username {
      font-size: 0.85rem;
      font-weight: 700;
      color: #fff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.8);
    }

    .ig-story-footer {
      position: absolute;
      bottom: 1.5rem;
      left: 1.5rem;
      right: 1.5rem;
      z-index: 2;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .ig-story-caption {
      font-size: 0.9rem;
      color: #ffffff;
      font-weight: 500;
      text-shadow: 0 2px 4px rgba(0,0,0,0.9);
      line-height: 1.4;
    }

    .btn-visit-story {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      padding: 0.8rem 1.2rem;
      background: linear-gradient(135deg, var(--neon-pink), var(--neon-purple));
      color: #fff;
      font-weight: 700;
      font-size: 0.85rem;
      border-radius: 100px;
      text-decoration: none;
      box-shadow: 0 8px 20px rgba(247, 37, 133, 0.4);
      transition: all 0.3s ease;
    }

    .btn-visit-story:hover {
      transform: scale(1.03);
      box-shadow: 0 12px 25px rgba(247, 37, 133, 0.6);
    }

    .grid-v4 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }

    .grid-v5 {
      display: grid;
      grid-template-columns: 1fr;
    }

    .full-card {
      background: linear-gradient(135deg, rgba(52, 211, 153, 0.08) 0%, var(--surface) 100%);
      border: 1px solid rgba(52, 211, 153, 0.25);
    }

    .statement-card {
      margin: 6rem 0;
      padding: 4rem 2rem;
      text-align: center;
      border-radius: 24px;
      background: linear-gradient(180deg, var(--surface) 0%, transparent 100%);
      border: 1px solid var(--border);
    }

    .statement-card h2 {
      font-size: clamp(1.8rem, 4vw, 3rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.2;
    }

    footer {
      border-top: 1px solid var(--border);
      padding: 3rem 0;
      display: flex;
      justify-content: space-between;
      color: var(--text-muted);
      font-size: 0.85rem;
    }

    /* --- RESPONSIVE MOBILE FIXES --- */
    @media (max-width: 768px) {
      .container {
        padding: 0 1.25rem;
      }

      .nav-container {
        padding: 1rem 1.25rem;
      }

      .timeline-container {
        padding: 0.75rem 1.25rem;
        gap: 1.25rem;
      }

      .timeline-link {
        font-size: 0.85rem;
      }

      .hero {
        min-height: auto;
        padding: 3rem 0 1.5rem 0;
      }

      .hero h1 {
        font-size: clamp(2.2rem, 8vw, 3rem);
        line-height: 1.1;
      }

      .hero p {
        font-size: 1rem;
      }

      .grid-v1, .grid-v2, .grid-v3, .grid-v4 {
        grid-template-columns: 1fr !important;
      }

      [style*="grid-column"] {
        grid-column: span 1 !important;
      }

      .bento-card {
        padding: 1.5rem;
      }

      .story-card-container {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 0.5rem;
      }

      .ig-story-card {
        max-width: 280px;
      }

      footer {
        flex-direction: column;
        gap: 0.75rem;
        text-align: center;
        padding: 2.5rem 0;
      }
    }

    @media (max-width: 380px) {
      .hero h1 {
        font-size: 2rem;
      }
      .bento-card {
        padding: 1.25rem;
      }
    }

    .lifecycle-carousel-wrapper {
      position: relative;
      width: 100%;
      overflow: hidden;
      margin-top: 1.5rem;
      mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
      -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
    }

    .lifecycle-track {
      display: flex;
      gap: 1.25rem;
      width: max-content;
      animation: infiniteScroll 25s linear infinite;
    }

    .lifecycle-carousel-wrapper:hover .lifecycle-track {
      animation-play-state: paused;
    }

    .lifecycle-step-card {
      width: 280px;
      flex-shrink: 0;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      transition: all 0.3s ease;
    }

    .lifecycle-step-card:hover {
      border-color: var(--neon-pink);
      box-shadow: 0 10px 25px -5px rgba(192, 132, 252, 0.2);
    }

    @keyframes infiniteScroll {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-50%);
      }
    }
  </style>
</head>
<body>

  <div class="bg-glow"></div>

  <nav class="nav">
    <div class="nav-container">
      <a href="#hero" class="nav-logo">MyPortfolio.</a>
      <div class="nav-right">
        <button class="theme-toggle" id="themeToggle">
          <i class="fa-solid fa-moon" id="themeIcon"></i>
          <span id="themeText">Dark</span>
        </button>
      </div>
    </div>
  </nav>

  <div class="story-timeline-nav" id="chapters">
    <div class="timeline-container">
      <a href="#sec-1" class="timeline-link active">Introduce</a>
      <a href="#sec-2" class="timeline-link">What I Do</a>
      <a href="#sec-3" class="timeline-link">Professional Projects</a>
      <a href="#sec-4" class="timeline-link">Community Project</a>
      <a href="#sec-5" class="timeline-link">Personal Project</a>
    </div>
  </div>

  <div class="container">

    <header class="hero" id="hero">
      <h1>Nik<br>Nik.</h1>
      <div class="card-meta">
        <div class="hero-badge">ERP Functional Consultant</div>
        <div class="hero-badge">System & Business Analyst</div>
      </div>
      <p>I analyze business processes, identify system gaps, and translate operational needs into practical system solutions.</p>
    </header>

    <section class="chapter-section" id="sec-1">
      <div class="chapter-header">
        <div class="chapter-tag" style="color: var(--neon-cyan);">Chapter I — Introduce</div>
        <h2 class="chapter-title">Hi, Let me introduce myself.</h2>
      </div>

      <div class="grid-v1">
        <div class="bento-card glow-cyan" style="grid-column: span 12;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-user"></i></div>
            <h3 class="card-title">Who am I?</h3>
          </div>
          <p class="card-desc">Call me <b>Nik</b>, a System and Business Analyst with a background in Information Systems and experience working with ERP systems, business processes, data, and software applications.</p>
          <p class="card-desc">I enjoy understanding how things work as a whole — not just looking at one feature or one problem, but seeing how people, processes, data, and systems are connected.</p>
          <p class="card-desc">My career has allowed me to work across different areas, from ERP and data migration to product data, system testing, process analysis, and application development. These experiences shaped the way I approach problems today: understand the situation first, find the gap, and then figure out what can be improved.</p>
        </div>
        <!--<div class="bento-card glow-purple" style="grid-column: span 4;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-user-tag"></i></div>
            <h3 class="card-title">Who am I?</h3>
          </div>
          <p class="card-desc">System and Business Analyst with a background in Information Systems and experience working with ERP systems, business processes, data, and software applications.</p>
        </div>

        <div class="bento-card glow-purple" style="grid-column: span 4;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-gears"></i></div>
            <h3 class="card-title">What I enjoyed?</h3>
          </div>
          <p class="card-desc">I enjoy understanding how things work as a whole — not just looking at one feature or one problem, but seeing how people, processes, data, and systems are connected.</p>
        </div>

        <div class="bento-card glow-purple" style="grid-column: span 4;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-file-circle-check"></i></div>
            <h3 class="card-title">What I've done?</h3>
          </div>
          <p class="card-desc">My career has allowed me to work across different areas, from ERP and data migration to product data, system testing, process analysis, and application development.</p>
          <p class="card-desc">These experiences shaped the way I approach problems today: understand the situation first, find the gap, and then figure out what can be improved.</p>
        </div>
      </div>-->

        <div class="bento-card glow-cyan" style="grid-column: span 6;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-spinner"></i></div>
            <h3 class="card-title">How do I work?</h3>
          </div>
          <p class="card-desc">Usually start by understanding the current situation.
          <p class="card-desc">Look at how a process works, what the system is doing, what users actually need, and where the gap exists between them. From there, I break the problem down, explore possible solutions, and work with the people involved to turn the solution into something practical.</p>
          <p class="card-desc">Enjoy being involved across the process — from understanding the problem and designing a solution to testing it and making sure it works in the real workflow.</p>
          <p class="card-desc">I value autonomy. <b>Work best when I’m trusted</b> with the responsibility to understand a problem, explore the possibilities, and take ownership of finding a solution.</p>
        </div>

        <div class="bento-card glow-cyan" style="grid-column: span 6;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-face-laugh-beam"></i></div>
            <h3 class="card-title">What I enjoyed?</h3>
          </div>
          <p class="card-desc">I enjoy problems that require me to <b>understand, connect, and improve</b>.
          <p class="card-desc">Especially problems where the answer isn’t immediately obvious — where I need to understand a business process, identify what is missing or not working, connect it with the system, and find a better way forward.</p>
          <p class="card-desc">I don’t want to solve the same problem every day.</p>
          <p class="card-desc">I enjoy working on different challenges because each one gives me an opportunity to learn something new, understand a different part of a business, and use what I know to create a practical solution.</p>
        </div>
      </div>
    </section>

    <section class="chapter-section" id="sec-2">
      <div class="chapter-header">
        <div class="chapter-tag" style="color: var(--neon-pink);">Chapter II — What I do</div>
        <h2 class="chapter-title">How I Approach Problems</h2>
      </div>
      <div class="grid-v1">
        <div class="bento-card glow-pink" style="grid-column: span 4;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
            <h3 class="card-title">Analyze</h3>
          </div>
          <p class="card-desc">I start by understanding how a system, process, or requirement works as a whole.</p>
          <p class="card-desc">Look at how users work, how the system behaves, how data moves, and how different parts of a process are connected.</p>
          <p class="card-desc">This helps me understand the actual problem before thinking about a solution.</p>
        </div>
        <div class="bento-card glow-pink" style="grid-column: span 4;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-object-ungroup"></i></div>
            <h3 class="card-title">Find Gaps</h3>
          </div>
          <p class="card-desc">Once I understand the current situation, I look for gaps between what the business or users need and what the current system or process provides.</p>
          <p class="card-desc">These gaps can come from unclear requirements, inefficient processes, missing system capabilities, or behavior that does not match the actual workflow.</p>
        </div>
        <div class="bento-card glow-pink" style="grid-column: span 4;">
          <div class="card-meta" style="gap: 1rem;">
            <div class="card-icon"><i class="fa-solid fa-object-group"></i></div>
            <h3 class="card-title" style="font-size: 1.493rem;">Design Solutions</h3>
          </div>
          <p class="card-desc">After identifying the gap, I explore practical ways to address it.</p>
          <p class="card-desc">I work between business users and technical teams to translate the problem into a solution that can actually be implemented, then validate it through testing and real workflows to make sure it works as intended.</p>
        </div>
        <div class="bento-card glow-pink" style="grid-column: span 12;">
          <div class="card-meta" style="justify-content: center; margin-bottom: 0rem;">
            <h3 class="card-title">My Problem-Solving Workflow</h3>
          </div>
          <div class="lifecycle-carousel-wrapper">
            <div class="lifecycle-track">
              <div class="lifecycle-step-card">
                <div class="step-header"><span class="step-number"><b>1. Understand Current State</b></span></div>
                <div class="step-desc">Understand where the system or process currently stands.</div>
              </div>
              <div class="lifecycle-step-card">
              <div class="step-header"><span class="step-number"><b>2. Understand Workflow</b></span></div>
              <div class="step-desc">See how the process actually works in practice</div>
            </div>
            <div class="lifecycle-step-card">
              <div class="step-header"><span class="step-number"><b>3. Identify Users Thinking</b></span></div>
              <div class="step-desc">Understand the problems users face in the current workflow.</div>
            </div>
            <div class="lifecycle-step-card">
              <div class="step-header"><span class="step-number"><b>4. Find the Gap</b></span></div>
              <div class="step-desc">Identify what the current system or process does not cover.</div>
            </div>
            <div class="lifecycle-step-card">
              <div class="step-header"><span class="step-number"><b>5. Develop Solution</b></span></div>
              <div class="step-desc">Find or build a practical solution to address the gap.</div>
            </div>
            <div class="lifecycle-step-card">
              <div class="step-header"><span class="step-number"><b>6. Challenge the Solution</b></span></div>
              <div class="step-desc">Check whether the solution creates other problems.</div>
            </div>
            <div class="lifecycle-step-card">
              <div class="step-header"><span class="step-number"><b>7. Implement</b></span></div>
              <div class="step-desc">Put the agreed solution into practice.</div>
            </div>
            <div class="lifecycle-step-card">
              <div class="step-header"><span class="step-number"><b>8. Improve</b></span></div>
              <div class="step-desc">Learn from the result and refine where necessary.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </section>

    <section class="chapter-section" id="sec-4">
      <div class="chapter-header">
        <div class="chapter-tag" style="color: var(--neon-emerald);">Chapter IV — Community Project</div>
        <h2 class="chapter-title">Church Event</h2>
      </div>

      <div class="bento-card glow-emerald" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch; margin-bottom: 1.5rem;">

        <div style="display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div class="card-meta" style="gap: 1rem;">
              <img src="https://ui-avatars.com/api/?name=Lorem+Ipsum&background=f72585&color=fff" class="card-icon" alt="Avatar">
            </div>
            <div class="card-meta" style="gap: 1rem;">
              <h3 class="card-title">Cinema Concept</h3>
            </div>
            <p class="card-desc">This Christmas church event was designed around a cinema concept, with the venue, seating, and overall experience arranged to recreate a cinema environment.</p>
            <p class="card-desc" style="margin-bottom: 0;">I was involved in the event committee and saw firsthand how the ticket and seat-selection process was planned to be handled manually, which led me to identify an opportunity to digitize the process.</p>
          </div>
          <div class="card-meta" style="margin-top: 1.5rem; margin-bottom: 0;">
            <span class="tech-tag"><i class="fa-brands fa-instagram"></i> Instagram</span>
          </div>
        </div>

        <div style="width: 100%; aspect-ratio: 1 / 1; border-radius: 16px; border: 1px dashed var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; align-self: center;">
          <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">
            <i class="fa-regular fa-image" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
            <span>1:1 Photo Placeholder</span>
          </div>
        </div>

        <hr style="border: none; border-top: 1px solid rgba(16, 185, 129, 0.3); margin: 0; grid-column: span 2;">

        <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
          <h3 class="card-title" style="margin-bottom: 0;">The First Concept</h3>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
              01
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Physical seat markers</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Physical markers were used to indicate selected seats.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-purple); flex-shrink: 0;">
              02
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Manual Seat Selection</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Seat availability had to be checked manually.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-pink); flex-shrink: 0;">
              03
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Manual Seat Changes</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Seat changes required to be updated manually.</p>
            </div>
          </div>
          <h3 class="card-title" style="margin-top: 1rem; margin-bottom: 0;">What Was My Idea?</h3>
          <p class="card-desc" style="margin-bottom: 0;">Proposed digitizing the seat-selection process by replacing the physical seat markers with a digital seat-management system.</p>
          <p class="card-desc" style="margin-bottom: 0;">The goal was to make seat availability easier to manage while creating a more seamless experience that matched the cinema concept of the event.</p>
        </div>

        <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
          <h3 class="card-title" style="margin-bottom: 0;">The Opportunities</h3>
          <p class="card-desc" style="margin-bottom: 0;">I noticed that seat availability and any changes to seat selections would still need to be managed manually.</p>
          <p class="card-desc" style="margin-bottom: 0;">I saw an opportunity to digitize the process, making seat management more efficient and better aligned with the cinema concept of the event.</p>
          <h3 class="card-title">The Impacts</h3>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
              01
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Digitalize Seat Tracking</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Clearer visibility of seat status.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-purple); flex-shrink: 0;">
              02
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Clearly See Seat Availability</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Easier access to seat availability.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-pink); flex-shrink: 0;">
              03
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Easily Change Seat Selection</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Seat updates without changing physical records.</p>
            </div>
          </div>
        </div>

        <hr style="border: none; border-top: 1px solid rgba(16, 185, 129, 0.3); margin: 0; grid-column: span 2;">

        <div style="display: grid; grid-template-columns: auto 1fr; gap: 2.5rem; align-items: stretch; grid-column: span 2;">
          <div style="display: flex; justify-content: flex-start; align-items: center;">
            <a href="https://www.instagram.com/reel/DSM6cjHEnCy/?utm_source=ig_web_copy_link&igsi=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer" style="display: block; width: 220px; aspect-ratio: 9 / 16; border-radius: 20px; border: 1px solid var(--border); background: #000; position: relative; overflow: hidden; text-decoration: none; flex-shrink: 0; transition: border-color 0.3s ease;">
              <img src="/storage/attachments/portfolio/ig-thumnail.jpg" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.85;" alt="Video Thumbnail">
              <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 52px; height: 52px; border-radius: 50%; background: rgba(0, 0, 0, 0.6); border: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                <i class="fa-solid fa-play" style="color: #ffffff; font-size: 1.1rem; margin-left: 3px;"></i>
              </div>
            </a>
          </div>

          <div style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <h3 class="card-title" style="margin-bottom: 0.5rem; font-size: 1.35rem;">System Results</h3>
              <p class="card-desc" style="margin-bottom: 0.75rem;">The system was successfully used for four consecutive weeks, giving the event team a digital way to manage seat availability, selections, and changes during the event.</p>
              <p class="card-desc" style="margin-bottom: 1.5rem;">It made the process easier to operate while supporting the cinema-themed experience.</p>
              <h3 class="card-title" style="margin-bottom: 0.5rem; font-size: 1.35rem;">Real-World Impacts</h3>
              <p class="card-desc" style="margin-bottom: 0.75rem;">The cinema concept also gained strong engagement on Instagram, with the event content reaching <b>72K views</b>.</p>
              <p class="card-desc" style="margin-bottom: 0;">This became an additional reflection of how the concept resonated with the audience beyond the operational side of the event.</p>
            </div>
            <div class="card-meta" style="margin-top: 1.5rem; margin-bottom: 0; display: flex; gap: 0.75rem; flex-wrap: wrap;">
              <a href="#" class="tech-tag-link" style="text-decoration: none; padding: 0.4rem 1rem;">Lets Try</a>
              <a href="https://www.instagram.com/reel/DSM6cjHEnCy/?utm_source=ig_web_copy_link&igsi=MzRlODBiNWFlZA==" class="tech-tag" style="text-decoration: none; padding: 0.4rem 1rem; border-color: var(--neon-pink); color: var(--neon-pink);" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-instagram"></i> Watch Reels on Instagram
              </a>
            </div>
          </div>
        </div>

        <hr style="border: none; border-top: 1px solid rgba(16, 185, 129, 0.3); margin: 0; grid-column: span 2;">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch; grid-column: span 2;">
          <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
            <h3 class="card-title" style="margin-bottom: 0;">How I Built It?</h3>
            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem;">
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
                  01
                </div>
                <div>
                  <h3 class="card-title" style="margin-bottom: 0; font-size: 1.25rem;">Setup & Approach</h3>
                </div>
              </div>
              <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-left: 1rem; border-left: 2px solid var(--border); margin-left: 1.25rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Built without a framework</p>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Small codebase: 5 PHP files + 1 external CSS</p>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Developed locally using XAMPP</p>
                </div>
              </div>
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
                  02
                </div>
                <div>
                  <h3 class="card-title" style="margin-bottom: 0; font-size: 1.25rem;">System Design</h3>
                </div>
              </div>
              <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-left: 1rem; border-left: 2px solid var(--border); margin-left: 1.25rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Designed the database yourself</p>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Managed database through phpMyAdmin</p>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Built the system around the actual seat-booking flow</p>
                </div>
              </div>
              <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
                  03
                </div>
                <div>
                  <h3 class="card-title" style="margin-bottom: 0; font-size: 1.25rem;">Deployment</h3>
                </div>
              </div>
              <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-left: 1rem; border-left: 2px solid var(--border); margin-left: 1.25rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Ran the application from your own PC as the server</p>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Used ngrok to expose the local application</p>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                  <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Actual event setup: 1 laptop + 1 tablet for ushers</p>
                </div>
              </div>
            </div>
          </div>

          <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
            <h3 class="card-title" style="margin-bottom: 0;">System Architecture Diagram</h3>
            <div style="width: 100%; aspect-ratio: 1 / 1; border-radius: 16px; border: 1px dashed var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; align-self: center;">
              <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">
                <i class="fa-regular fa-image" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                <span>1:1 Photo Placeholder</span>
              </div>
            </div>
            <div class="card-meta" style="margin-top: 1.5rem; margin-bottom: 0;">
              <span class="tech-tag"><i class="fa-brands fa-php"></i> PHP</span>
              <span class="tech-tag"><i class="fa-brands fa-css3-alt"></i> CSS</span>
              <span class="tech-tag"><i class="fa-brands fa-js"></i> JavaScript</span>
              <span class="tech-tag"><i class="fa-brands fa-mysql"></i> MySQL</span>
              <span class="tech-tag"><i class="fa-solid fa-server"></i> ngrok</span>
            </div>
          </div>
        </div>

      </div>
    </section>

    <section class="chapter-section" id="sec-5">
      <div class="chapter-header">
        <div class="chapter-tag" style="color: var(--neon-emerald);">Chapter V — Personal Project</div>
        <h2 class="chapter-title">Everyday</h2>
      </div>

      <div class="bento-card glow-emerald" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch; margin-bottom: 1.5rem;">

        <div style="display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div class="card-meta" style="gap: 1rem;">
              <img src="https://ui-avatars.com/api/?name=Lorem+Ipsum&background=f72585&color=fff" class="card-icon" alt="Avatar">
            </div>
            <div class="card-meta" style="gap: 1rem;">
              <h3 class="card-title">Everyday</h3>
            </div>
            <p class="card-desc">This Christmas church event was designed around a cinema concept, with the venue, seating, and overall experience arranged to recreate a cinema environment.</p>
            <p class="card-desc" style="margin-bottom: 0;">I was involved in the event committee and saw firsthand how the ticket and seat-selection process was planned to be handled manually, which led me to identify an opportunity to digitize the process.</p>
          </div>
          <div class="card-meta" style="margin-top: 1.5rem; margin-bottom: 0;">
            <span class="tech-tag"><i class="fa-brands fa-instagram"></i> Instagram</span>
          </div>
        </div>

        <div style="width: 100%; aspect-ratio: 1 / 1; border-radius: 16px; border: 1px dashed var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; align-self: center;">
          <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">
            <i class="fa-regular fa-image" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
            <span>1:1 Photo Placeholder</span>
          </div>
        </div>

        <hr style="border: none; border-top: 1px solid rgba(16, 185, 129, 0.3); margin: 0; grid-column: span 2;">

        <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
          <h3 class="card-title" style="margin-bottom: 0;">The First Concept</h3>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
              01
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Physical seat markers</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Physical markers were used to indicate selected seats.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-purple); flex-shrink: 0;">
              02
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Manual Seat Selection</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Seat availability had to be checked manually.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-pink); flex-shrink: 0;">
              03
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Manual Seat Changes</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Seat changes required to be updated manually.</p>
            </div>
          </div>
          <h3 class="card-title" style="margin-top: 1rem; margin-bottom: 0;">What Was My Idea?</h3>
          <p class="card-desc" style="margin-bottom: 0;">Proposed digitizing the seat-selection process by replacing the physical seat markers with a digital seat-management system.</p>
          <p class="card-desc" style="margin-bottom: 0;">The goal was to make seat availability easier to manage while creating a more seamless experience that matched the cinema concept of the event.</p>
        </div>

        <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
          <h3 class="card-title" style="margin-bottom: 0;">The Opportunities</h3>
          <p class="card-desc" style="margin-bottom: 0;">I noticed that seat availability and any changes to seat selections would still need to be managed manually.</p>
          <p class="card-desc" style="margin-bottom: 0;">I saw an opportunity to digitize the process, making seat management more efficient and better aligned with the cinema concept of the event.</p>
          <h3 class="card-title">The Impacts</h3>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
              01
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Digitalize Seat Tracking</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Clearer visibility of seat status.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-purple); flex-shrink: 0;">
              02
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Clearly See Seat Availability</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Easier access to seat availability.</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1.25rem; background: var(--surface); border: 1px solid var(--border); border-radius: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-pink); flex-shrink: 0;">
              03
            </div>
            <div>
              <h4 style="font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.25rem;">Easily Change Seat Selection</h4>
              <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Seat updates without changing physical records.</p>
            </div>
          </div>
        </div>

        <hr style="border: none; border-top: 1px solid rgba(16, 185, 129, 0.3); margin: 0; grid-column: span 2;">

        <div style="display: flex; justify-content: flex-start; align-items: center;">
          <a href="https://www.instagram.com/reel/DSM6cjHEnCy/?utm_source=ig_web_copy_link&igsi=MzRlODBiNWFlZA==" target="_blank" rel="noopener noreferrer" style="display: block; width: 220px; aspect-ratio: 9 / 16; border-radius: 20px; border: 1px solid var(--border); background: #000; position: relative; overflow: hidden; text-decoration: none; flex-shrink: 0; transition: border-color 0.3s ease;">
            <img src="/storage/attachments/portfolio/ig-thumnail.jpg" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.85;" alt="Video Thumbnail">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 52px; height: 52px; border-radius: 50%; background: rgba(0, 0, 0, 0.6); border: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
              <i class="fa-solid fa-play" style="color: #ffffff; font-size: 1.1rem; margin-left: 3px;"></i>
            </div>
          </a>
        </div>

        <div style="display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <h3 class="card-title" style="margin-bottom: 0.5rem; font-size: 1.35rem;">System Results</h3>
            <p class="card-desc" style="margin-bottom: 0.75rem;">The system was successfully used for four consecutive weeks, giving the event team a digital way to manage seat availability, selections, and changes during the event.</p>
            <p class="card-desc" style="margin-bottom: 1.5rem;">It made the process easier to operate while supporting the cinema-themed experience.</p>
            <h3 class="card-title" style="margin-bottom: 0.5rem; font-size: 1.35rem;">Real-World Impacts</h3>
            <p class="card-desc" style="margin-bottom: 0.75rem;">The cinema concept also gained strong engagement on Instagram, with the event content reaching <b>72K views</b>.</p>
            <p class="card-desc" style="margin-bottom: 0;">This became an additional reflection of how the concept resonated with the audience beyond the operational side of the event.</p>
          </div>
          <div class="card-meta" style="margin-top: 1.5rem; margin-bottom: 0; display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="#" class="tech-tag-link" style="text-decoration: none; padding: 0.4rem 1rem;">Lets Try</a>
            <a href="https://www.instagram.com/reel/DSM6cjHEnCy/?utm_source=ig_web_copy_link&igsi=MzRlODBiNWFlZA==" class="tech-tag" style="text-decoration: none; padding: 0.4rem 1rem; border-color: var(--neon-pink); color: var(--neon-pink);" target="_blank" rel="noopener noreferrer">
              <i class="fa-brands fa-instagram"></i> Watch Reels on Instagram
            </a>
          </div>
        </div>

        <hr style="border: none; border-top: 1px solid rgba(16, 185, 129, 0.3); margin: 0; grid-column: span 2;">

        <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
          <h3 class="card-title" style="margin-bottom: 0;">How I Built It?</h3>
          <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
              <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
                01
              </div>
              <div>
                <h3 class="card-title" style="margin-bottom: 0; font-size: 1.25rem;">Setup & Approach</h3>
              </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-left: 1rem; border-left: 2px solid var(--border); margin-left: 1.25rem;">
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Built without a framework</p>
              </div>
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Small codebase: 5 PHP files + 1 external CSS</p>
              </div>
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Developed locally using XAMPP</p>
              </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
              <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
                02
              </div>
              <div>
                <h3 class="card-title" style="margin-bottom: 0; font-size: 1.25rem;">System Design</h3>
              </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-left: 1rem; border-left: 2px solid var(--border); margin-left: 1.25rem;">
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Designed the database yourself</p>
              </div>
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Managed database through phpMyAdmin</p>
              </div>
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Built the system around the actual seat-booking flow</p>
              </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
              <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: var(--neon-cyan); flex-shrink: 0;">
                03
              </div>
              <div>
                <h3 class="card-title" style="margin-bottom: 0; font-size: 1.25rem;">Deployment</h3>
              </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-left: 1rem; border-left: 2px solid var(--border); margin-left: 1.25rem;">
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Ran the application from your own PC as the server</p>
              </div>
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Used ngrok to expose the local application</p>
              </div>
              <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; margin: 0;">Actual event setup: 1 laptop + 1 tablet for ushers</p>
              </div>
            </div>
          </div>
        </div>

        <div style="display: flex; flex-direction: column; justify-content: flex-start; gap: 1rem;">
          <h3 class="card-title" style="margin-bottom: 0;">System Architecture Diagram</h3>
          <div style="width: 100%; aspect-ratio: 1 / 1; border-radius: 16px; border: 1px dashed var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; align-self: center;">
            <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">
              <i class="fa-regular fa-image" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
              <span>1:1 Photo Placeholder</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="chapter-section" id="sec-4">
      <div class="chapter-header">
        <div class="chapter-tag" style="color: var(--neon-emerald);">Chapter IV — Experimental Lab</div>
        <h2 class="chapter-title">UI Components & Tools</h2>
      </div>

      <div class="grid-v4">
        <div class="bento-card glow-emerald">
          <div class="card-icon"><i class="fa-solid fa-palette"></i></div>
          <h3 class="card-title">Design Systems</h3>
          <p class="card-desc">Dokumentasi komponen UI reusable dengan variabel CSS fleksibel.</p>
          <div class="card-meta"><span class="tech-tag">Figma</span></div>
        </div>

        <div class="bento-card glow-purple">
          <div class="card-icon"><i class="fa-solid fa-bolt"></i></div>
          <h3 class="card-title">Micro Animations</h3>
          <p class="card-desc">Eksperimen transisi CSS3 dan Framer Motion interaktif.</p>
          <div class="card-meta"><span class="tech-tag">Animation</span></div>
        </div>

        <div class="bento-card glow-cyan">
          <div class="card-icon"><i class="fa-solid fa-code"></i></div>
          <h3 class="card-title">Blade Components</h3>
          <p class="card-desc">Pengembangan struktur Blade view modular pada Laravel framework.</p>
          <div class="card-meta"><span class="tech-tag">Laravel</span></div>
        </div>
      </div>
    </section>

    <section class="chapter-section" id="sec-5">
      <div class="chapter-header">
        <div class="chapter-tag" style="color: var(--neon-amber);">Chapter V — Profile Overview</div>
        <h2 class="chapter-title">About & Experience</h2>
      </div>

      <div class="grid-v5">
        <div class="bento-card full-card glow-emerald" style="flex-direction: row; gap: 2rem; align-items: center; flex-wrap: wrap;">
          <div class="card-icon" style="width: 80px; height: 80px; flex-shrink: 0;">
            <img src="https://ui-avatars.com/api/?name=Lorem+Ipsum&background=random&color=fff" alt="Avatar" style="width:100%; height:100%; border-radius:12px; object-fit: cover;">
          </div>
          <div>
            <h3 class="card-title">Developer & Content Creator</h3>
            <p class="card-desc" style="max-width: 700px; margin-bottom: 1rem;">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.
            </p>
            <div class="card-meta">
              <span class="tech-tag"><i class="fa-brands fa-github"></i> GitHub</span>
              <span class="tech-tag"><i class="fa-brands fa-linkedin"></i> LinkedIn</span>
              <span class="tech-tag"><i class="fa-brands fa-instagram"></i> Instagram</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="statement-card">
      <h2>"Kode yang bersih dan konten yang engaging adalah kunci menciptakan dampak digital yang bertahan lama."</h2>
    </section>

    <footer>
      <div>&copy; 2026 Developer Portfolio.</div>
      <div>Built with passion & precision.</div>
    </footer>

  </div>

  <script>
    // 1. Theme Switcher Logic
    const toggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');

    toggleBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      document.documentElement.setAttribute('data-theme', newTheme);
      
      if (newTheme === 'light') {
        themeIcon.className = 'fa-solid fa-sun';
        themeText.textContent = 'Light';
      } else {
        themeIcon.className = 'fa-solid fa-moon';
        themeText.textContent = 'Dark';
      }
    });

    // 2. Smooth Scroll & Auto Highlighting for 5 Sections
    const timelineLinks = document.querySelectorAll('.timeline-link');
    const sections = document.querySelectorAll('.chapter-section');

    timelineLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href');
        const targetSection = document.querySelector(targetId);

        if (targetSection) {
          targetSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    const observerOptions = {
      root: null,
      rootMargin: '-35% 0px -45% 0px',
      threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const activeId = `#${entry.target.id}`;
          
          timelineLinks.forEach(link => {
            if (link.getAttribute('href') === activeId) {
              link.classList.add('active');
            } else {
              link.classList.remove('active');
            }
          });
        }
      });
    }, observerOptions);

    sections.forEach(section => observer.observe(section));
  </script>
</body>
</html>