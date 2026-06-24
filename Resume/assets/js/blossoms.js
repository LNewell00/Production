/**
 * Cherry Blossom Canvas Animation
 * Soft petals drifting — tuned to the cream / purple palette
 */
(function () {
  const canvas = document.getElementById('blossom-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  let W, H, petals = [];
  const COUNT = 38;

  /* petal colour variants — cream-pink to soft purple */
  const COLORS = [
    'rgba(242,156,179,0.55)',
    'rgba(242,156,179,0.35)',
    'rgba(220,140,168,0.45)',
    'rgba(200,190,240,0.40)',
    'rgba(183,181,202,0.35)',
    'rgba(255,220,230,0.50)',
  ];

  function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
  }

  function randomPetal(fromTop) {
    return {
      x:        Math.random() * W,
      y:        fromTop ? -20 : Math.random() * H,
      r:        4 + Math.random() * 5,          // petal radius
      rx:       (4 + Math.random() * 5) * 1.6,  // half-width (ellipse)
      ry:       3 + Math.random() * 3,           // half-height
      rot:      Math.random() * Math.PI * 2,
      rotSpeed: (Math.random() - 0.5) * 0.03,
      vx:       (Math.random() - 0.5) * 0.6,
      vy:       0.35 + Math.random() * 0.55,
      sway:     Math.random() * Math.PI * 2,
      swaySpeed:0.008 + Math.random() * 0.012,
      swayAmp:  0.4 + Math.random() * 0.6,
      color:    COLORS[Math.floor(Math.random() * COLORS.length)],
      opacity:  0.5 + Math.random() * 0.5,
    };
  }

  function drawPetal(p) {
    ctx.save();
    ctx.translate(p.x, p.y);
    ctx.rotate(p.rot);
    ctx.beginPath();
    /* draw a simple 5-petal flower using ellipse arcs */
    for (let i = 0; i < 5; i++) {
      ctx.save();
      ctx.rotate((i / 5) * Math.PI * 2);
      ctx.scale(1, 0.55);
      ctx.beginPath();
      ctx.ellipse(0, -p.rx * 0.55, p.rx * 0.45, p.rx * 0.75, 0, 0, Math.PI * 2);
      ctx.fillStyle = p.color;
      ctx.fill();
      ctx.restore();
    }
    /* centre dot */
    ctx.beginPath();
    ctx.arc(0, 0, p.rx * 0.18, 0, Math.PI * 2);
    ctx.fillStyle = 'rgba(255,200,210,0.7)';
    ctx.fill();
    ctx.restore();
  }

  function init() {
    resize();
    petals = Array.from({ length: COUNT }, () => randomPetal(false));
    window.addEventListener('resize', resize);
  }

  function tick() {
    ctx.clearRect(0, 0, W, H);
    petals.forEach(p => {
      p.sway += p.swaySpeed;
      p.x += p.vx + Math.sin(p.sway) * p.swayAmp;
      p.y += p.vy;
      p.rot += p.rotSpeed;
      if (p.y > H + 30) Object.assign(p, randomPetal(true));
      drawPetal(p);
    });
    requestAnimationFrame(tick);
  }

  init();
  tick();
})();