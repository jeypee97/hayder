=<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GAL TRHAYDERS AI | Modern Broker Platform</title>
  <meta name="description" content="GAL TRHAYDERS AI is a next-generation trading system built to analyze markets, automate execution, and simplify smart trading." />
  <meta name="theme-color" content="#071325" />
  <style>
    :root{
      --bg:#061121;
      --bg2:#091a33;
      --bg3:#0d2243;
      --panel:#0b1730;
      --panel2:#102447;
      --card:rgba(255,255,255,.06);
      --card2:rgba(255,255,255,.04);
      --line:rgba(255,255,255,.10);
      --text:#f4f8ff;
      --muted:#afbddb;
      --blue:#2a86ff;
      --cyan:#27d7ff;
      --gold:#e2bb73;
      --gold2:#ffd997;
      --green:#21d78f;
      --red:#ff6b81;
      --shadow:0 28px 90px rgba(0,0,0,.34);
      --radius:28px;
      --max:1240px;
    }
    *{box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{
      margin:0;
      font-family:Inter,Arial,Helvetica,sans-serif;
      color:var(--text);
      background:
        radial-gradient(circle at 10% 8%, rgba(42,134,255,.18), transparent 24%),
        radial-gradient(circle at 88% 14%, rgba(39,215,255,.10), transparent 18%),
        radial-gradient(circle at 85% 84%, rgba(226,187,115,.09), transparent 22%),
        linear-gradient(180deg, #030915 0%, #08152a 42%, #0b1f3d 100%);
      overflow-x:hidden;
    }
    body::before{
      content:"";
      position:fixed; inset:0;
      background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
      background-size:40px 40px;
      mask-image:linear-gradient(180deg, transparent, #000 12%, #000 88%, transparent);
      pointer-events:none;
      opacity:.65;
      z-index:-4;
    }
    body::after{
      content:"";
      position:fixed; inset:-10%;
      background:
        radial-gradient(circle at 20% 25%, rgba(42,134,255,.10), transparent 20%),
        radial-gradient(circle at 80% 20%, rgba(39,215,255,.10), transparent 22%),
        radial-gradient(circle at 60% 80%, rgba(226,187,115,.08), transparent 18%);
      filter:blur(32px);
      animation:bgMorph 18s ease-in-out infinite alternate;
      pointer-events:none;
      z-index:-5;
    }
    @keyframes bgMorph{
      0%{transform:translate3d(0,0,0) scale(1)}
      50%{transform:translate3d(20px,-18px,0) scale(1.04)}
      100%{transform:translate3d(-14px,22px,0) scale(1.08)}
    }
    a{text-decoration:none;color:inherit}
    img{max-width:100%;display:block}
    .wrap{width:min(calc(100% - 28px), var(--max));margin:0 auto}
    .section{padding:96px 0;position:relative}
    .section::before{
      content:"";
      position:absolute;left:0;right:0;top:0;height:1px;
      background:linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
    }

    .fx-layer{position:fixed; inset:0; pointer-events:none; z-index:-2; overflow:hidden}
    .orb{position:absolute; border-radius:50%; filter:blur(28px); opacity:.32; animation:floatOrb 16s ease-in-out infinite}
    .orb.a{width:220px;height:220px;left:-60px;top:120px;background:rgba(42,134,255,.24)}
    .orb.b{width:170px;height:170px;right:8%;top:18%;background:rgba(39,215,255,.16);animation-delay:-4s}
    .orb.c{width:190px;height:190px;left:11%;bottom:10%;background:rgba(226,187,115,.14);animation-delay:-8s}
    @keyframes floatOrb{
      0%,100%{transform:translateY(0) translateX(0)}
      50%{transform:translateY(-22px) translateX(12px)}
    }
    .ticker-strip{
      position:fixed; top:76px; left:0; right:0; z-index:70;
      background:rgba(4,12,24,.84);
      border-top:1px solid rgba(255,255,255,.06);
      border-bottom:1px solid rgba(255,255,255,.06);
      backdrop-filter:blur(14px);
      overflow:hidden;
    }
    .ticker-track{
      display:flex; gap:28px; width:max-content; padding:10px 0;
      animation:tickerMove 26s linear infinite;
      font-size:.88rem; font-weight:900; color:#d9e6ff;
    }
    .ticker-item{display:flex; align-items:center; gap:10px; padding-left:20px}
    .ticker-up{color:#86ffcb}
    .ticker-down{color:#ff9fb0}
    @keyframes tickerMove{
      from{transform:translateX(0)}
      to{transform:translateX(-50%)}
    }

    .topbar{
      position:sticky; top:0; z-index:90;
      background:rgba(4,11,24,.78);
      backdrop-filter:blur(18px);
      border-bottom:1px solid rgba(255,255,255,.08);
    }
    .topbar-inner{
      width:min(calc(100% - 28px), var(--max)); margin:0 auto;
      display:flex; align-items:center; justify-content:space-between; gap:16px;
      padding:14px 0;
    }
    .brand{display:flex; align-items:center; gap:12px; font-weight:950; letter-spacing:.01em}
    .brand-logo{
      width:52px;
      height:52px;
      border-radius:16px;
      overflow:hidden;
      border:1px solid rgba(255,255,255,.12);
      box-shadow:var(--shadow);
      background:rgba(255,255,255,.05);
      flex:none;
    }
    .brand-logo img{
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
    }
    .brand-badge{
      width:46px;height:46px;border-radius:16px;display:grid;place-items:center;color:#fff;font-weight:950;
      background:linear-gradient(135deg,var(--blue),var(--cyan),var(--gold));
      background-size:220% 220%;
      animation:badgeFlow 7s ease infinite;
      box-shadow:var(--shadow);
    }
    @keyframes badgeFlow{
      0%{background-position:0% 50%}
      50%{background-position:100% 50%}
      100%{background-position:0% 50%}
    }
    .brand small{display:block;color:var(--muted);font-size:.78rem;font-weight:800;margin-top:2px}
    .nav{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
    .nav a.link{
      position:relative;color:var(--muted);font-size:.96rem;font-weight:900;transition:.25s ease;
    }
    .nav a.link::after{
      content:"";position:absolute;left:0;bottom:-8px;width:0;height:3px;border-radius:999px;
      background:linear-gradient(90deg,var(--cyan),var(--gold));transition:.25s ease;
    }
    .nav a.link:hover{color:#fff}
    .nav a.link:hover::after{width:100%}

    .btn{
      display:inline-flex;align-items:center;justify-content:center;gap:10px;
      padding:15px 22px;border-radius:16px;border:1px solid transparent;
      font-weight:950;transition:.28s ease;position:relative;overflow:hidden;cursor:pointer;
    }
    .btn::before{
      content:"";position:absolute;inset:0;
      background:linear-gradient(120deg, transparent 18%, rgba(255,255,255,.18) 50%, transparent 82%);
      transform:translateX(-120%);transition:.7s ease;
    }
    .btn:hover::before{transform:translateX(120%)}
    .btn:hover{transform:translateY(-3px)}
    .btn.primary{
      color:#081424;background:linear-gradient(135deg,var(--gold),var(--gold2));
      box-shadow:0 18px 40px rgba(226,187,115,.18);
    }
    .btn.secondary{
      color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan));
      box-shadow:0 18px 40px rgba(42,134,255,.20);
    }
    .btn.ghost{
      color:#fff;background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.10);
    }

    .hero{
      min-height:calc(100svh - 76px);
      display:flex;align-items:center;padding:122px 0 76px;
    }
    .hero-grid{
      display:grid;
      grid-template-columns:1.02fr .98fr;
      gap:34px;
      align-items:center;
    }

    .eyebrow{
      display:inline-flex;align-items:center;gap:10px;
      padding:10px 14px;border-radius:999px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.10);
      font-size:.8rem;font-weight:950;letter-spacing:.07em;text-transform:uppercase;color:#d7e3ff;
      animation:pulseSoft 3s ease-in-out infinite;
    }
    .eyebrow::before{
      content:"";width:8px;height:8px;border-radius:50%;
      background:linear-gradient(135deg,var(--cyan),var(--gold));
      box-shadow:0 0 14px rgba(39,215,255,.35);
    }
    @keyframes pulseSoft{
      0%,100%{transform:translateY(0)}
      50%{transform:translateY(-2px)}
    }
    h1{
      margin:18px 0;
      font-size:clamp(2.7rem, 6vw, 5.8rem);
      line-height:.92;
      letter-spacing:-.055em;
      max-width:760px;
    }
    .grad{
      background:linear-gradient(135deg,#ffffff 0%, #9fd8ff 28%, #59d9ff 56%, #ffdc9a 100%);
      background-size:220% 220%;
      -webkit-background-clip:text;background-clip:text;color:transparent;
      animation:textFlow 8s ease infinite;
    }
    @keyframes textFlow{
      0%{background-position:0% 50%}
      50%{background-position:100% 50%}
      100%{background-position:0% 50%}
    }
    .lead{max-width:720px;color:var(--muted);font-size:1.08rem;line-height:1.9;margin:0 0 26px}
    .bullet-grid{
      display:grid;
      grid-template-columns:repeat(2,minmax(0,1fr));
      gap:12px;
      margin:22px 0 28px;
      max-width:700px;
    }
    .bullet{
      display:flex;align-items:flex-start;gap:12px;
      padding:16px 18px;border-radius:20px;
      background:rgba(255,255,255,.05);
      border:1px solid rgba(255,255,255,.08);
      box-shadow:var(--shadow);
      transition:.28s ease;
    }
    .bullet:hover{transform:translateY(-4px)}
    .bicon{
      width:34px;height:34px;min-width:34px;border-radius:12px;
      display:grid;place-items:center;
      background:linear-gradient(135deg, rgba(39,215,255,.16), rgba(226,187,115,.16));
      border:1px solid rgba(255,255,255,.10);
      font-weight:900;
    }
    .hero-actions{display:flex;gap:14px;flex-wrap:wrap;margin:16px 0 18px}
    .urgency{
      color:#ffd997;
      font-size:.95rem;
      font-weight:900;
      margin-top:8px;
    }

    .broker-stage{
      position:relative;
      min-height:700px;
      border-radius:34px;
      padding:18px;
      background:
        linear-gradient(160deg, rgba(255,255,255,.10), rgba(255,255,255,.04));
      border:1px solid rgba(255,255,255,.10);
      box-shadow:var(--shadow);
      overflow:hidden;
      animation:stageFloat 6s ease-in-out infinite;
      transform-style:preserve-3d;
    }
    @keyframes stageFloat{
      0%,100%{transform:translateY(0)}
      50%{transform:translateY(-8px)}
    }
    .stage-bg{
      position:absolute; inset:18px;
      border-radius:24px;
      background:
        linear-gradient(180deg, rgba(4,11,24,.22), rgba(4,11,24,.72)),
        url('brand-bg.jpg') center/cover no-repeat;
      border:1px solid rgba(255,255,255,.08);
      overflow:hidden;
    }
    .stage-bg::after{
      content:"";
      position:absolute; inset:0;
      background:
        radial-gradient(circle at 80% 20%, rgba(39,215,255,.14), transparent 18%),
        linear-gradient(180deg, rgba(3,9,21,.14), rgba(3,9,21,.58));
    }
    .candles{
      position:absolute; right:32px; top:80px; bottom:42px; width:40%;
      display:flex; align-items:flex-end; justify-content:space-between; gap:8px;
      opacity:.88;
    }
    .candle{
      width:12%;
      position:relative;
      animation:candleMove 2.8s ease-in-out infinite;
    }
    .candle:nth-child(2){animation-delay:-.4s}
    .candle:nth-child(3){animation-delay:-.8s}
    .candle:nth-child(4){animation-delay:-1.2s}
    .candle:nth-child(5){animation-delay:-1.6s}
    .candle:nth-child(6){animation-delay:-2s}
    .candle::before{
      content:""; position:absolute; left:50%; transform:translateX(-50%);
      width:2px; top:-18px; bottom:-18px; background:rgba(255,255,255,.24);
    }
    .candle .body{
      width:100%; border-radius:8px 8px 4px 4px; position:relative;
      box-shadow:0 0 18px rgba(39,215,255,.18);
    }
    .candle.up .body{background:linear-gradient(180deg, #64ffca, #1dd58f)}
    .candle.down .body{background:linear-gradient(180deg, #ff9db1, #ff6b81)}
    @keyframes candleMove{
      0%,100%{transform:translateY(0)}
      50%{transform:translateY(-10px)}
    }

    .stage-overlay{
      position:absolute; inset:18px; z-index:2; padding:28px;
      display:flex; flex-direction:column; justify-content:space-between;
    }
    .market-head{
      display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;
    }
    .market-chip{
      display:inline-flex; align-items:center; gap:10px;
      padding:10px 14px; border-radius:999px;
      background:rgba(7,17,36,.72);
      border:1px solid rgba(255,255,255,.10);
      font-size:.82rem; font-weight:950;
      backdrop-filter:blur(10px);
    }
    .market-chip::before{
      content:""; width:8px; height:8px; border-radius:50%;
      background:#73ffc7; box-shadow:0 0 16px rgba(115,255,199,.4);
    }
    .stage-title{
      max-width:420px; margin-top:16px;
    }
    .stage-title h3{
      margin:0 0 8px; font-size:clamp(2rem,4vw,3.2rem); letter-spacing:-.04em; color:var(--gold2);
    }
    .stage-title p{margin:0;color:#dce6ff;line-height:1.8}
    .floating-stack{
      display:grid; gap:14px; width:min(320px, 100%);
    }
    .float-panel{
      padding:16px; border-radius:18px;
      background:rgba(7,17,36,.80);
      border:1px solid rgba(255,255,255,.10);
      backdrop-filter:blur(10px);
      box-shadow:var(--shadow);
      animation:panelDrift 5s ease-in-out infinite;
    }
    .float-panel:nth-child(2){animation-delay:-1.5s}
    .float-panel:nth-child(3){animation-delay:-3s}
    .float-panel span{display:block;color:#a7bceb;font-size:.82rem;margin-bottom:6px}
    .float-panel strong{font-size:1.08rem}
    @keyframes panelDrift{
      0%,100%{transform:translateY(0)}
      50%{transform:translateY(-8px)}
    }

    .section-head{max-width:760px;margin-bottom:30px}
    .section-head h2{
      margin:0 0 14px;font-size:clamp(2rem,4vw,3.5rem);line-height:1.02;letter-spacing:-.045em;
    }
    .section-head p{margin:0;color:var(--muted);line-height:1.85}

    .grid-3,.grid-2,.steps{display:grid;gap:20px}
    .grid-3{grid-template-columns:repeat(3,minmax(0,1fr))}
    .grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}
    .steps{grid-template-columns:repeat(4,minmax(0,1fr))}

    .card,.step,.highlight,.cta-box,.notice{
      padding:28px;border-radius:24px;
      background:linear-gradient(180deg, rgba(255,255,255,.07), rgba(255,255,255,.04));
      border:1px solid rgba(255,255,255,.08);
      box-shadow:var(--shadow);
      position:relative; overflow:hidden; transition:.3s ease;
    }
    .card:hover,.step:hover,.highlight:hover,.cta-box:hover,.notice:hover{transform:translateY(-8px)}
    .card::after,.step::after,.highlight::after,.cta-box::after,.notice::after{
      content:""; position:absolute; inset:auto -30px -40px auto; width:120px; height:120px; border-radius:50%;
      background:radial-gradient(circle, rgba(39,215,255,.12), transparent 70%);
    }
    .icon{
      width:56px;height:56px;border-radius:18px;display:grid;place-items:center;
      background:linear-gradient(135deg, rgba(39,215,255,.16), rgba(226,187,115,.14));
      border:1px solid rgba(255,255,255,.10);
      margin-bottom:14px;font-size:1.35rem;animation:iconTilt 6s linear infinite;
    }
    @keyframes iconTilt{
      0%,100%{transform:rotate(0deg)}
      50%{transform:rotate(8deg)}
    }
    .card h3,.step h3,.highlight h3,.cta-box h3,.notice h3{margin:0 0 10px;font-size:1.22rem}
    .card p,.step p,.highlight p,.cta-box p,.notice p{margin:0;color:var(--muted);line-height:1.8}
    .step-no{
      width:54px;height:54px;border-radius:18px;display:grid;place-items:center;
      background:linear-gradient(135deg,var(--gold),var(--gold2));
      color:#081424;font-weight:950;margin-bottom:14px;box-shadow:0 16px 36px rgba(226,187,115,.20);
    }

    .highlight{
      background:linear-gradient(135deg, rgba(42,134,255,.12), rgba(39,215,255,.08), rgba(226,187,115,.08));
    }
    .proof-quote{
      font-size:clamp(1.35rem,2.4vw,2rem);
      line-height:1.25;
      color:#fff;
      font-weight:900;
      letter-spacing:-.03em;
    }
    .tag-rotator{
      display:grid; gap:12px; margin-top:18px;
    }
    .tag{
      padding:15px 16px;
      border-radius:18px;
      background:rgba(255,255,255,.05);
      border:1px solid rgba(255,255,255,.08);
      font-weight:850;
      transition:.25s ease;
    }
    .tag:hover{transform:translateX(6px)}

    .cta-box{
      text-align:center;
      background:
        radial-gradient(circle at top right, rgba(39,215,255,.14), transparent 24%),
        radial-gradient(circle at bottom left, rgba(226,187,115,.12), transparent 22%),
        linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,.04));
    }
    .cta-box h2{
      margin:0 0 12px;
      font-size:clamp(2rem,4vw,3.6rem);
      line-height:1.04;
      letter-spacing:-.045em;
    }
    .phone-line{
      color:#ffd997;
      font-size:1.1rem;
      font-weight:950;
      margin:16px 0 18px;
    }
    .cta-actions{display:flex;gap:14px;flex-wrap:wrap;justify-content:center}

    .notice{
      background:rgba(255,107,129,.08);
      border-color:rgba(255,107,129,.20);
    }

    .footer{
      padding:42px 0 84px;
      color:var(--muted);
      border-top:1px solid rgba(255,255,255,.08);
    }
    .footer-grid{
      display:grid;
      grid-template-columns:1.15fr .85fr;
      gap:24px;
      align-items:start;
    }
    .footer-links{
      display:grid;
      grid-template-columns:repeat(2,minmax(0,1fr));
      gap:12px;
    }
    .footer-links a{
      padding:12px 14px;
      border-radius:18px;
      background:rgba(255,255,255,.05);
      border:1px solid rgba(255,255,255,.08);
      font-weight:850;
      transition:.25s ease;
    }
    .footer-links a:hover{transform:translateY(-2px)}

    .sticky-cta{
      position:sticky; bottom:14px; z-index:70;
      width:min(calc(100% - 22px), 860px);
      margin:0 auto;
      display:flex;align-items:center;justify-content:space-between;gap:16px;
      padding:14px 16px;
      border-radius:22px;
      background:rgba(7,17,36,.84);
      backdrop-filter:blur(18px);
      border:1px solid rgba(255,255,255,.10);
      box-shadow:var(--shadow);
      animation:barFloat 4s ease-in-out infinite;
    }
    @keyframes barFloat{
      0%,100%{transform:translateY(0)}
      50%{transform:translateY(-3px)}
    }
    .sticky-cta strong{display:block}
    .sticky-cta span{display:block;color:var(--muted);font-size:.94rem}

    .reveal{
      opacity:0;
      transform:translateY(36px) scale(.985);
      transition:opacity .85s ease, transform .85s cubic-bezier(.2,.7,.2,1);
    }
    .reveal.in{opacity:1;transform:none}

    @media (max-width:1100px){
      .steps{grid-template-columns:repeat(2,minmax(0,1fr))}
      .hero-grid,.grid-3,.grid-2,.footer-grid{grid-template-columns:1fr}
    }
    @media (max-width:980px){
      .hero{padding-top:116px}
      .broker-stage{
        min-height:620px;
        padding:14px;
      }
      .stage-bg,
      .stage-overlay{
        inset:14px;
      }
      .bullet-grid{grid-template-columns:1fr}
      .candles{
        display:flex;
        width:48%;
        right:18px;
        top:100px;
        bottom:26px;
        gap:6px;
        opacity:.84;
      }
      .floating-stack{
        width:min(100%, 320px);
      }
      .ticker-strip{top:72px}
    }
    @media (max-width:720px){
      .section{padding:74px 0}
      .hero{padding:108px 0 58px}
      .nav a.link{display:none}
      .hero-actions,.cta-actions{flex-direction:column}
      .btn{width:100%}
      .steps,.footer-links{grid-template-columns:1fr}
      .sticky-cta{flex-direction:column;align-items:stretch;text-align:left}

      .ticker-strip{
        display:block;
        top:72px;
      }
      .ticker-track{
        font-size:.78rem;
        gap:18px;
        padding:8px 0;
      }
      .ticker-item{
        gap:8px;
        padding-left:14px;
      }

      .hero-grid{
        gap:22px;
      }
      .bullet-grid{
        grid-template-columns:1fr;
      }

      .broker-stage{
        min-height:640px;
        padding:12px;
        border-radius:26px;
      }
      .stage-bg,
      .stage-overlay{
        inset:12px;
        border-radius:20px;
      }

      .stage-overlay{
        padding:18px;
        justify-content:space-between;
      }

      .market-head{
        display:flex;
        flex-direction:column;
        gap:16px;
        align-items:stretch;
      }

      .stage-title{
        max-width:100%;
      }
      .stage-title h3{
        font-size:clamp(1.55rem,7vw,2.15rem);
      }
      .stage-title p{
        font-size:.95rem;
        line-height:1.7;
      }

      .candles{
        display:flex;
        width:44%;
        right:10px;
        top:118px;
        bottom:22px;
        gap:4px;
        opacity:.78;
      }
      .candle{
        width:13%;
      }
      .candle::before{
        top:-12px;
        bottom:-12px;
      }

      .floating-stack{
        width:100%;
        gap:10px;
      }
      .float-panel{
        padding:13px 14px;
        border-radius:15px;
      }
      .float-panel span{
        font-size:.78rem;
      }
      .float-panel strong{
        font-size:.96rem;
        line-height:1.35;
      }

      .sticky-cta{
        width:min(calc(100% - 18px), 860px);
      }
    }

    @media (max-width:720px){.support-grid{grid-template-columns:1fr !important;}}

    .book-card-wrap{
      display:grid;
      grid-template-columns:.88fr 1.12fr;
      gap:24px;
      align-items:center;
      margin-top:8px;
    }
    .book-cover-card{
      padding:18px;
      border-radius:28px;
      background:linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.04));
      border:1px solid rgba(255,255,255,.08);
      box-shadow:var(--shadow);
      position:relative;
      overflow:hidden;
    }
    .book-cover-card::after{
      content:"";
      position:absolute; inset:auto -30px -40px auto;
      width:140px; height:140px; border-radius:50%;
      background:radial-gradient(circle, rgba(226,187,115,.16), transparent 70%);
    }
    .book-cover-card img{
      width:100%;
      height:520px;
      object-fit:cover;
      border-radius:20px;
      border:1px solid rgba(255,255,255,.08);
      background:#08152a;
      box-shadow:0 18px 40px rgba(0,0,0,.28);
    }
    .book-badge{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:10px 14px;
      border-radius:999px;
      background:rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.10);
      font-size:.8rem;
      font-weight:950;
      letter-spacing:.06em;
      text-transform:uppercase;
      color:#dce6ff;
      margin-bottom:14px;
    }
    .book-badge::before{
      content:"";
      width:8px; height:8px; border-radius:50%;
      background:linear-gradient(135deg,var(--cyan),var(--gold));
      box-shadow:0 0 14px rgba(39,215,255,.35);
    }
    .book-copy-card{
      padding:30px;
      border-radius:28px;
      background:linear-gradient(135deg, rgba(42,134,255,.10), rgba(39,215,255,.08), rgba(226,187,115,.08));
      border:1px solid rgba(255,255,255,.08);
      box-shadow:var(--shadow);
    }
    .book-copy-card h3{
      margin:0 0 12px;
      font-size:clamp(1.8rem, 4vw, 3rem);
      line-height:1.02;
      letter-spacing:-.04em;
    }
    .book-copy-card p{
      margin:0 0 16px;
      color:var(--muted);
      line-height:1.9;
      font-size:1rem;
    }
    .book-points{
      display:grid;
      gap:12px;
      margin:18px 0 24px;
    }
    .book-point{
      display:flex;
      align-items:flex-start;
      gap:12px;
      padding:15px 16px;
      border-radius:18px;
      background:rgba(255,255,255,.05);
      border:1px solid rgba(255,255,255,.08);
      box-shadow:var(--shadow);
    }
    .book-point .bp-icon{
      width:34px; height:34px; min-width:34px;
      border-radius:12px;
      display:grid; place-items:center;
      background:linear-gradient(135deg, rgba(39,215,255,.16), rgba(226,187,115,.16));
      border:1px solid rgba(255,255,255,.10);
      font-weight:900;
    }
    @media (max-width:980px){
      .book-card-wrap{grid-template-columns:1fr}
    }
    @media (max-width:720px){
      .book-copy-card,.book-cover-card{padding:18px}
      .book-cover-card img{height:420px}
    }

  </style>
</head>
<body>
  <div class="fx-layer">
    <span class="orb a"></span>
    <span class="orb b"></span>
    <span class="orb c"></span>
  </div>

  <header class="topbar">
    <div class="topbar-inner">
      <div class="brand">
        <div class="brand-logo">
          <img src="https://res.cloudinary.com/draqpi1df/image/upload/f_auto,q_auto/logo.jpeg_ylmvmx" alt="GAL TRHAYDERS logo">
        </div>
        <div>
          GAL TRHAYDERS AI
          <small>Modern Broker Experience</small>
        </div>
      </div>
      <nav class="nav">
        <a class="link" href="#home">Home</a>
        <a class="link" href="#about">About</a>
        <a class="link" href="#book">Book</a>
        <a class="link" href="#contact">Contact</a>
        <a class="btn ghost" href="https://trhayders.com/login" target="_blank" rel="noopener">Login</a>
        <a class="btn secondary" href="https://trhayders.com/register" target="_blank" rel="noopener">Signup</a>
      </nav>
    </div>
  </header>

  <div class="ticker-strip">
    <div class="ticker-track">
      <div class="ticker-item"><span>EUR/USD</span> <span class="ticker-up">+1.24%</span></div>
      <div class="ticker-item"><span>BTC/USD</span> <span class="ticker-up">+3.11%</span></div>
      <div class="ticker-item"><span>XAU/USD</span> <span class="ticker-down">-0.42%</span></div>
      <div class="ticker-item"><span>NASDAQ</span> <span class="ticker-up">+0.88%</span></div>
      <div class="ticker-item"><span>GBP/JPY</span> <span class="ticker-down">-0.27%</span></div>
      <div class="ticker-item"><span>EUR/USD</span> <span class="ticker-up">+1.24%</span></div>
      <div class="ticker-item"><span>BTC/USD</span> <span class="ticker-up">+3.11%</span></div>
      <div class="ticker-item"><span>XAU/USD</span> <span class="ticker-down">-0.42%</span></div>
      <div class="ticker-item"><span>NASDAQ</span> <span class="ticker-up">+0.88%</span></div>
      <div class="ticker-item"><span>GBP/JPY</span> <span class="ticker-down">-0.27%</span></div>
    </div>
  </div>

  <main>
    <section class="hero" id="home">
      <div class="wrap hero-grid">
        <div class="reveal">
          <div class="eyebrow">AI Trading • Automated Execution • Smarter Decisions</div>
          <h1>🔥 Trade Smarter. <span class="grad">Earn Better.</span> Let AI Do The Work.</h1>
          <p class="lead">
            Introducing GAL TRHAYDERS AI — a powerful next-generation trading system designed to analyze the market and execute trades intelligently, so you don’t have to.
          </p>

          <div class="bullet-grid">
            <div class="bullet"><div class="bicon">✓</div><div><strong>No Experience Needed</strong><br><span style="color:var(--muted)">Built for users who want structure without years of chart stress.</span></div></div>
            <div class="bullet"><div class="bicon">✓</div><div><strong>No Emotional Trading</strong><br><span style="color:var(--muted)">Reduce impulsive decisions with intelligent system logic.</span></div></div>
            <div class="bullet"><div class="bicon">✓</div><div><strong>Fully Automated AI System</strong><br><span style="color:var(--muted)">Market scanning, setup recognition, and automated action flow.</span></div></div>
            <div class="bullet"><div class="bicon">✓</div><div><strong>Built for Smart Earners Worldwide</strong><br><span style="color:var(--muted)">Scalable for beginners, active traders, and global users.</span></div></div>
          </div>

          <div class="hero-actions">
            <a class="btn secondary" href="https://trhayders.com/singup" target="_blank" rel="noopener">Signup</a>
            <a class="btn primary" href="https://trhayders.com/register" target="_blank" rel="noopener">Login</a>

            <a class="btn ghost" href="https://flutterwave.com/pay/mr-blockchain" target="_blank" rel="noopener">Buy Now</a>
          </div>

          <div class="urgency">⚠️ Start now and access the system through secure account signup and login.</div>
        </div>

        <div class="broker-stage reveal tilt-card">
          <div class="stage-bg"></div>

          <div class="candles">
            <div class="candle up" style="height:28%"><div class="body" style="height:80%"></div></div>
            <div class="candle up" style="height:42%"><div class="body" style="height:82%"></div></div>
            <div class="candle down" style="height:36%"><div class="body" style="height:84%"></div></div>
            <div class="candle up" style="height:58%"><div class="body" style="height:86%"></div></div>
            <div class="candle up" style="height:74%"><div class="body" style="height:82%"></div></div>
            <div class="candle down" style="height:64%"><div class="body" style="height:86%"></div></div>
          </div>

          <div class="stage-overlay">
            <div class="market-head">
              <div>
                <div class="market-chip">Market System Active</div>
                <div class="stage-title">
                  <div style="width:96px;height:96px;border-radius:24px;overflow:hidden;border:1px solid rgba(255,255,255,.12);box-shadow:var(--shadow);background:rgba(255,255,255,.05);margin-bottom:14px">
                    <img src="https://res.cloudinary.com/draqpi1df/image/upload/f_auto,q_auto/logo.jpeg_ylmvmx" alt="GAL TRHAYDERS logo" style="width:100%;height:100%;object-fit:cover;display:block">
                  </div>
                  <h3>Broker-style trading UI</h3>
                  <p>A redesigned stage with movement, floating data panels, and a stronger financial-platform feel.</p>
                </div>
              </div>

              <div class="floating-stack">
                <div class="float-panel">
                  <span>Core Engine</span>
                  <strong>Real-Time AI Analysis</strong>
                </div>
                <div class="float-panel">
                  <span>Execution Layer</span>
                  <strong>Automated Trade Logic</strong>
                </div>
                <div class="float-panel">
                  <span>User Flow</span>
                  <strong>Signup → Login → Activate</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="problem">
      <div class="wrap">
        <div class="section-head reveal">
          <h2>Why most traders <span class="grad">struggle</span></h2>
          <p>
            Let’s be real. Most traders lose money because they lack time, consistency, emotional control, and a system that can keep working with discipline.
          </p>
        </div>

        <div class="grid-2">
          <div class="card reveal">
            <div class="icon">⏱️</div>
            <h3>Not enough time</h3>
            <p>Many traders cannot stay in front of charts long enough to monitor opportunities and manage entries correctly.</p>
          </div>
          <div class="card reveal">
            <div class="icon">🧠</div>
            <h3>Emotional decisions</h3>
            <p>Fear, greed, hesitation, and overconfidence distort decision-making and damage consistency over time.</p>
          </div>
          <div class="card reveal">
            <div class="icon">📉</div>
            <h3>Weak strategy discipline</h3>
            <p>Without proper structure, even good setups can be ruined by poor timing, poor risk control, and poor follow-through.</p>
          </div>
          <div class="card reveal">
            <div class="icon">⚠️</div>
            <h3>Long-term profitability is hard</h3>
            <p>Even experienced traders struggle to remain profitable when discipline breaks down and market pressure rises.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="solution">
      <div class="wrap">
        <div class="section-head reveal">
          <h2>Meet your <span class="grad">AI trading advantage</span></h2>
          <p>
            GAL TRHAYDERS AI changes everything by turning market complexity into a cleaner automated experience.
          </p>
        </div>

        <div class="grid-3">
          <div class="card reveal">
            <div class="icon">📊</div>
            <h3>Analyze market data in real time</h3>
            <p>The system watches market conditions continuously to identify stronger setups and cleaner entry logic.</p>
          </div>
          <div class="card reveal">
            <div class="icon">🎯</div>
            <h3>Identify high-probability opportunities</h3>
            <p>Built to filter noise and focus attention on more structured opportunities instead of random decision-making.</p>
          </div>
          <div class="card reveal">
            <div class="icon">⚙️</div>
            <h3>Execute automatically</h3>
            <p>No stress. No guesswork. Just smart automation working with discipline and speed.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="how">
      <div class="wrap">
        <div class="section-head reveal">
          <h2>How it <span class="grad">works</span></h2>
          <p>
            A simple flow designed to move users from account setup to live system use without confusion.
          </p>
        </div>

        <div class="steps">
          <div class="step reveal">
            <div class="step-no">1</div>
            <h3>Sign Up</h3>
            <p>Create your account and get verified so your profile is ready for secure access.</p>
          </div>
          <div class="step reveal">
            <div class="step-no">2</div>
            <h3>Fund, Connect & Set Up</h3>
            <p>Fund and link your trading account securely with the proper system connection flow.</p>
          </div>
          <div class="step reveal">
            <div class="step-no">3</div>
            <h3>Activate AI</h3>
            <p>Turn on the system and allow the automated logic to begin working.</p>
          </div>
          <div class="step reveal">
            <div class="step-no">4</div>
            <h3>Monitor & Earn</h3>
            <p>Track system activity and performance while staying focused on life and other priorities.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="features">
      <div class="wrap">
        <div class="section-head reveal">
          <h2>Powerful features designed for <span class="grad">results</span></h2>
        </div>

        <div class="grid-3">
          <div class="card reveal"><div class="icon">🤖</div><h3>Advanced AI Algorithms</h3><p>Smarter logic for detection, analysis, and action flow.</p></div>
          <div class="card reveal"><div class="icon">⚡</div><h3>Automated Trade Execution</h3><p>Move faster with less manual friction and cleaner execution timing.</p></div>
          <div class="card reveal"><div class="icon">🛡️</div><h3>Risk Management System</h3><p>Built with disciplined structure to support better control.</p></div>
          <div class="card reveal"><div class="icon">📡</div><h3>Real-Time Market Analysis</h3><p>Continuous data processing for a more active market view.</p></div>
          <div class="card reveal"><div class="icon">🖥️</div><h3>User-Friendly Dashboard</h3><p>Designed to be clear enough for beginners and clean enough for pros.</p></div>
          <div class="card reveal"><div class="icon">🌍</div><h3>Scalable for Beginners & Pros</h3><p>Built to support wider adoption without losing usability.</p></div>
        </div>
      </div>
    </section>

    <section class="section" id="about">
      <div class="wrap">
        <div class="section-head reveal">
          <h2>Why GAL TRHAYDERS AI <span class="grad">stands out</span></h2>
          <p>
            We are not just another trading platform. We are building a future-driven financial system powered by AI and backed by innovation.
          </p>
        </div>

        <div class="grid-2">
          <div class="highlight reveal">
            <h3>Built with authority</h3>
            <p>
              Built by Great Adetula Limited (GAL) and connected to the author of the popular book <strong>Mr. Blockchain</strong>, the platform is positioned for innovation, scale, and long-term relevance.
            </p>
          </div>
          <div class="highlight reveal">
            <h3>Designed for smarter growth</h3>
            <p>
              Designed for global scalability, focused on automation and efficiency, and created to support users who want technology to work harder on their behalf.
            </p>
          </div>
        </div>

        <div class="highlight reveal" style="margin-top:20px">
          <div class="proof-quote">“The future of trading is already here.”</div>
          <div class="tag-rotator">
            <div class="tag">“Let AI Trade While You Live Your Life”</div>
            <div class="tag">“From Stressful Trading to Smart Automation”</div>
            <div class="tag">“Don’t Trade Harder — Trade Smarter”</div>
          </div>
        </div>
      </div>
    </section>


    <section class="section" id="book">
      <div class="wrap">
        <div class="section-head reveal">
          <h2>The book behind the <span class="grad">vision</span></h2>
          <p>
            Get a copy of <strong>Mr. Blockchain</strong> and go deeper into the ideas shaping digital finance, blockchain awareness, and the future of technology.
          </p>
        </div>

        <div class="book-card-wrap">
          <div class="book-cover-card reveal">
            <img src="https://res.cloudinary.com/draqpi1df/image/upload/v1775590089/book-cover_pevua9.jpg" alt="Mr. Blockchain book cover">
          </div>

          <div class="book-copy-card reveal">
            <div class="book-badge">Featured Book</div>
            <h3>Buy <span class="grad">Mr. Blockchain</span> today</h3>
            <p>
              This is more than a title. It is a guide for readers who want to understand blockchain in simpler language and position themselves ahead in a fast-changing digital economy.
            </p>
            <p>
              If you want a stronger understanding of where technology, finance, and opportunity are going, this book gives you a practical starting point with a clear message and a future-focused perspective.
            </p>

            <div class="book-points">
              <div class="book-point">
                <div class="bp-icon">📘</div>
                <div><strong>Clear and practical</strong><br><span style="color:var(--muted)">Written to make blockchain ideas easier to grasp and more useful to everyday readers.</span></div>
              </div>
              <div class="book-point">
                <div class="bp-icon">🚀</div>
                <div><strong>Future-focused value</strong><br><span style="color:var(--muted)">A strong resource for people who want to grow with the next wave of finance and technology.</span></div>
              </div>
              <div class="book-point">
                <div class="bp-icon">🌍</div>
                <div><strong>Relevant for today</strong><br><span style="color:var(--muted)">Built for ambitious readers who want knowledge that can shift how they think and act.</span></div>
              </div>
            </div>

            <div class="hero-actions" style="margin:0">
              <a class="btn primary" href="https://flutterwave.com/pay/mr-blockchain" target="_blank" rel="noopener">Buy Now</a>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="section" id="contact">
      <div class="wrap">
        <div class="cta-box reveal">
          <h2>Ready to get <span class="grad">started?</span></h2>
          <p>
            Access the platform through secure account creation and login. No waitlist, no VIP gate, and no prelaunch copy — just direct action.
          </p>
          <div class="phone-line">📞 Call / WhatsApp: 07080069456</div>
          <div class="cta-actions">
            <a class="btn secondary" href="https://trhayders.com/singup" target="_blank" rel="noopener">Signup</a>
            <a class="btn primary" href="https://trhayders.com/register" target="_blank" rel="noopener">Login</a>
          </div>
        </div>

        <div class="notice reveal" style="margin-top:20px">
          <h3>Important Notice</h3>
          <p>
            Trading involves risk and may result in loss of capital. GAL TRHAYDERS AI is a technology platform and does not guarantee profits. Past performance is not indicative of future results.
          </p>
        </div>
      </div>
    </section>
  </main>

  <div class="sticky-cta">
    <div>
      <strong>GAL TRHAYDERS AI is ready for account access</strong>
      <span>Modern broker-style landing page with direct Signup, Login, and Support actions.</span>
    </div>
    <a class="btn secondary" href="https://trhayders.com/singup" target="_blank" rel="noopener">Signup</a>
  </div>

  <footer class="footer">
    <div class="wrap footer-grid">
      <div>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;flex-wrap:wrap">
          <div style="width:56px;height:56px;border-radius:16px;overflow:hidden;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05)">
            <img src="logo.jpeg.jpg" alt="GAL TRHAYDERS logo" style="width:100%;height:100%;object-fit:cover;display:block">
          </div>
          <div style="font-size:1.12rem;font-weight:950;color:#fff">GAL TRHAYDERS AI</div>
        </div>
        <div style="line-height:1.85;margin-bottom:12px">
          GAL TRHAYDERS is a product of Great Adetula Limited (GAL).
        </div>
        <div style="line-height:1.9">
          📧 customer247@trhayders.com<br>
          📞 07080069456<br>
          🌐 <a href="https://trhayders.com" target="_blank" rel="noopener">www.trhayders.com</a>
        </div>
      </div>
      <div class="footer-links">
        <a href="terms.html">Terms & Conditions</a>
        <a href="privacy.html">Privacy Policy</a>
        <a href="risk.html">Risk Disclosure</a>
        <a href="aml-kyc.html">AML/KYC Policy</a>
      </div>
    </div>
  </footer>

  <script>
    const io = new IntersectionObserver((entries)=>{
      entries.forEach((entry, i)=>{
        if(entry.isIntersecting){
          entry.target.classList.add('in');
          entry.target.style.transitionDelay = (i % 4) * 0.06 + 's';
        }
      });
    },{threshold:.12});
    document.querySelectorAll('.reveal').forEach(el=>io.observe(el));

    const tiltCard = document.querySelector('.tilt-card');
    if (tiltCard && window.innerWidth > 980) {
      tiltCard.addEventListener('mousemove', (e) => {
        const rect = tiltCard.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const rx = ((y / rect.height) - 0.5) * -7;
        const ry = ((x / rect.width) - 0.5) * 8;
        tiltCard.style.transform = `perspective(1000px) rotateX(${rx}deg) rotateY(${ry}deg) translateY(-6px)`;
      });
      tiltCard.addEventListener('mouseleave', () => {
        tiltCard.style.transform = '';
      });
    }

    const orbs = document.querySelectorAll('.orb');
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      orbs.forEach((orb, i) => {
        const speed = (i + 1) * 0.08;
        orb.style.transform = `translateY(${y * speed}px)`;
      });
    }, { passive: true });
  </script>
</body>
</html>

