// CANVAS ILLUSTRATION
let illo = new Zdog.Illustration({
  element: ".zdog-canvas",
  zoom: 2.4,
});

// GROUPS RENDER
var frontLaptop = new Zdog.Group({
  addTo: illo,
});

var backLaptop = new Zdog.Group({
  addTo: illo,
});

var upperLaptop = new Zdog.Group({
  addTo: illo,
});

var underLaptop = new Zdog.Group({
  addTo: illo,
});

// LAPTOP BODY
let box = new Zdog.Box({
  addTo: backLaptop,
  width: 80,
  height: 60,
  depth: 5,
  stroke: false,
  color: "#1F5AA6",
});

box.copy({
  addTo: backLaptop,
  translate: { x: 0, y: 42, z: 26 },
  color: "#439FD9",
  rotate: { x: -8.2 },
  fill: true,
});

// LAPTOP KEYBOARD
let keyboard = new Zdog.Rect({
  addTo: frontLaptop,
  translate: { x: 0, y: 38, z: 22 },
  rotate: { x: -8.2 },
  width: 64,
  height: 25,
  stroke: 1,
  color: "#C9C5D9",
  fill: true,
});

keyboard.copy({
  translate: { x: 0, y: 47, z: 42 },
  width: 20,
  height: 12,
  stroke: 1,
  color: "#C9C5D9",
  fill: true,
});

// LAPTOP SCREEN
let screen = new Zdog.Rect({
  addTo: frontLaptop,
  translate: { x: 0, y: 0, z: 2.8 },
  width: 72,
  height: 48,
  stroke: 2,
  color: "#D9D9D9",
  fill: true,
});

// LAPTOP LOGO
let circle = new Zdog.Ellipse({
  addTo: backLaptop,
  translate: { x: 0, y: 0, z: -2.9 },
  diameter: 14,
  stroke: 0.8,
  color: "#F25835",
  fill: true,
});

new Zdog.Shape({
  addTo: circle,
  translate: { x: -1, y: -7, z: -1 },
  rotate: { z: 7 },
  path: [{ x: -1 }, { x: 1 }],
  stroke: 1.5,
  color: "#0F0",
});

// ANIMATE
let ticker = 0;
let cycleCount = 150;

function animate() {
  let progress = ticker / cycleCount;
  let tween = Zdog.easeInOut(progress % 1, 2);
  illo.rotate.y = tween * Zdog.TAU;

  ticker++;

  illo.updateRenderGraph();
  requestAnimationFrame(animate);
}

animate();
