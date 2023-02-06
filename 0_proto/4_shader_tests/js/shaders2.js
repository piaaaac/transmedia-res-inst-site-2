// ------------------------
var startSeconds = Date.now() / 1000;
console.log(startSeconds)
// ------------------------

// previous example adapted to a shader from shadertoy

var canvas = document.createElement('canvas')
canvas.width = window.innerWidth
canvas.height = window.innerHeight
document.body.appendChild(canvas)

var gl = canvas.getContext('webgl')

gl.clearColor(1, 0, 1, 1)
gl.clear(gl.COLOR_BUFFER_BIT)

var vertexShader = gl.createShader(gl.VERTEX_SHADER)
var vertexShaderSource = document.querySelector("#vertex-shader").text;
gl.shaderSource(vertexShader, vertexShaderSource)
gl.compileShader(vertexShader)

var fragmentShader = gl.createShader(gl.FRAGMENT_SHADER)
var fragmentShaderSource = document.querySelector("#fragment-shader").text;
gl.shaderSource(fragmentShader, fragmentShaderSource)
gl.compileShader(fragmentShader)

var program = gl.createProgram()
gl.attachShader(program, vertexShader)
gl.attachShader(program, fragmentShader)
gl.linkProgram(program)

var vertices = new Float32Array([
  1., 1.,  1.,-1.,  -1.,-1.,
  -1.,-1.,  -1.,1.,  1.,1.,
]);

var buffer = gl.createBuffer()
gl.bindBuffer(gl.ARRAY_BUFFER, buffer)
gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW)

gl.useProgram(program)
program.iTime = gl.getUniformLocation(program, 'iTime')
gl.uniform1f(program.iTime, Date.now() / 1000 - startSeconds)

program.position = gl.getAttribLocation(program, 'position')
gl.enableVertexAttribArray(program.position)
gl.vertexAttribPointer(program.position, 2, gl.FLOAT, false, 0, 0)

gl.drawArrays(gl.TRIANGLES, 0, vertices.length / 2)