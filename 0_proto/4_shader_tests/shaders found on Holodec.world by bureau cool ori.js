var e=Object.defineProperty,t=(t,i,s)=>(((t,i,s)=>{i in t?e(t,i,{enumerable:!0,configurable:!0,writable:!0,value:s}):t[i]=s})(t,"symbol"!=typeof i?i+"":i,s),s);import{S as i,m as s,P as o,O as n,M as a,g as r,c,r as h,o as l,a as u,t as m,b as d,p as v,d as f,W as p,e as w,f as g,h as b,U as y,V as x,R as M,E as T,i as V,L as C,j as D,k,T as z,l as A,n as S,q as W,s as O,u as H,v as P,F as L,w as U,x as E,y as j,z as _,A as F,B as Y,C as X,D as R,G as I,H as N}from"./vendor.6affd80e.js";var $={uniforms:{tDiffuse:{value:null},opacity:{value:1},mouse:{value:[0,0]},mouseDown:{value:0}},vertexShader:`

varying vec2 vUv;
void main() {
    vUv = uv;
    gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );
}

`,fragmentShader:`
  
    uniform float opacity;
    uniform sampler2D tDiffuse;
    varying vec2 vUv;
    uniform float mouseDown;
    uniform vec2 mouse;

    void main() {
        vec4 mA = texture2D(tDiffuse, vUv).rgba;

        vec2 nVuv = vUv;
        nVuv -= vec2(0.5);
        nVuv.x *= 1.0 - 2.0 * mA.r * distance(nVuv, mouse) * mouseDown;
        nVuv.y *= 1.0 - 2.0 * mA.r * distance(nVuv, mouse) * mouseDown;
        nVuv += vec2(0.5);


        vec4 texel = texture2D( tDiffuse, nVuv );
        gl_FragColor = opacity * texel;
    }
`
};function B(...e){return e[0].raw.reduce(((t,i,s)=>t+i+(e[s+1]||"")),"")}const q=B`
    varying vec2 vUv;
    varying vec2 ovUv;
    varying vec3 nPosition;

    uniform sampler2D map;
    uniform float amount;
    uniform float perc;
    uniform float time;
    uniform float ratio;
    uniform float opacity;
    uniform float scale;
    uniform vec2 offset;
    uniform vec2 ratioMulti;


    void main() {

        vec3 nPosition = position;

        vec2 nVuv = uv;
        ovUv = uv;

        vec4 t2 = texture2D(map, ovUv).rgba;

        nVuv.x -= 0.5;
        nVuv.y -= 0.5;

        nVuv *= vec2(1.0 + (1.0-opacity) * (0.5-distance(vec2(0.0), nVuv)) * 1.0);

        nVuv.x *= ratioMulti.x;
        nVuv.y *= ratioMulti.y;
        nVuv /= vec2(1.0 + scale);
        nVuv.x += offset.x;
        nVuv.y += offset.y;
        nVuv.x += 0.5;
        nVuv.y += 0.5;

        vec4 mvPosition = modelViewMatrix * vec4(nPosition, 1.0);
        gl_Position = projectionMatrix * mvPosition;
        vUv = nVuv;
    }
`,K=B`
    #include <packing>
    varying vec2 vUv;
    varying vec2 ovUv;
    varying vec3 nPosition;

    uniform sampler2D map;
    uniform float perc;
    uniform float time;
    // uniform float offset;
    uniform float ratio;
    uniform float opacity;
    uniform float stripHeight;
    uniform float xerox;
    uniform float dif;

    uniform vec2 pos;

    // 2D Random
    float random (in vec2 st) {
        return fract(sin(dot(st.xy,
                             vec2(12.9898,78.233)))
                     * 43758.5453123);
    }
    
    // 2D Noise based on Morgan McGuire @morgan3d
    // https://www.shadertoy.com/view/4dS3Wd
    float noise (in vec2 st) {
        vec2 i = floor(st);
        vec2 f = fract(st);
    
        // Four corners in 2D of a tile
        float a = random(i);
        float b = random(i + vec2(1.0, 0.0));
        float c = random(i + vec2(0.0, 1.0));
        float d = random(i + vec2(1.0, 1.0));
    
        // Smooth Interpolation
    
        // Cubic Hermine Curve.  Same as SmoothStep()
        vec2 u = f*f*(3.0-2.0*f);
        // u = smoothstep(0.,1.,f);
    
        // Mix 4 coorners percentages
        return mix(a, b, u.x) +
                (c - a)* u.y * (1.0 - u.x) +
                (d - b) * u.x * u.y;
    }

    vec3 czm_saturation(vec3 rgb, float adjustment)
    {
        // Algorithm from Chapter 16 of OpenGL Shading Language
        const vec3 W = vec3(0.2125, 0.7154, 0.0721);
        vec3 intensity = vec3(dot(rgb, W));
        return mix(intensity, rgb, adjustment);
    }


    void main() {

      vec2 pos = vec2(vUv*2.0);

      // Use the noise function
      // float n = noise(pos);

        // float amount = (sin(time*0.2 + perc * 5.0)*0.5+0.5) * n;
        float amount = (sin(time*0.2 + perc * 5.0)*0.5+0.5);

        vec2 nVuv = vUv;
        nVuv -= vec2(0.5);
        // nVuv.x *= 1.0 - amount * ((sin(time*0.5+nVuv.y)*5.0));
        nVuv += vec2(0.5);

        vec4 t2 = texture2D(map, nVuv).rgba;

        // vec4 outC = t2 * (1.0 + 0.5) * dAvrg;

        // float multi = sin(ovUv.y * 60.0*stripWidthAmount + ovUv.x * 60.0*stripWidthAmount + time*2.5*offset + perc)*0.5+0.5;
        // float multi2 = sin(ovUv.y * 60.0*stripWidthAmount + ovUv.x * 60.0*stripWidthAmount + 3.1+ time*2.5*offset + perc)*0.5+0.5;
        
        // nVuv += vec2(0.5);

      // t2.rgb = t2.rgb * (1.0-amount)*(nVuv.y) + (vec3(1.0)-t2.rgb) * (amount);
        // t2.rgb *= vec3(1.4);
        t2.rgb = czm_saturation(t2.rgb, 1.5);
        vec2 novUv = ovUv;
        novUv -= vec2(0.5);
        float alpha = distance(vec2(0.0), novUv);
        novUv += vec2(0.5);

        gl_FragColor = vec4(vec3(t2), (1.0-alpha*2.0) * opacity);
        // gl_FragColor = vec4(1.0, 0.0, 0.0, 1.0);
    }
`;class G extends i{constructor(){super({vertexShader:q,fragmentShader:K,transparent:!0,side:0,uniforms:{map:{value:null},time:{value:0},ratio:{value:0},perc:{value:0},ratioMulti:{value:[1,1]},scale:{value:1},dif:{value:0},opacity:{value:0},offset:{value:[1,1]},size:{value:[0,0]},pos:{value:[0,0]}}})}}const J=s();var Q=(...e)=>J.on(...e),Z=(...e)=>J.off(...e),ee=(...e)=>J.emit(...e);const te=new o(1,1,1,1);class ie{constructor({addTo:e,texture:i,index:s,total:o}={}){t(this,"tick",((e,t,i,s)=>{this.mesh.position.set(this.pos[0]*innerWidth,this.pos[1]*innerHeight,0),this.material.uniforms.time.value=e,this.material.uniforms.opacity.value=this.tweenObjO.value*s})),this.addTo=e,this.texture=i,this.index=s,this.total=o,this.object=new n,this.addTo.add(this.object),this.tweenObjO={value:0},this.size=[0,0],this.pos=[0,0],this.scale=1,this.material=new G,this.material.uniforms.map.value=this.texture,this.material.uniforms.perc.value=this.index/this.total,this.material.uniforms.ratio.value=16/9,this.material.uniforms.time.value=1,this.mesh=new a(te,this.material),this.object.add(this.mesh),this.scaleX=this.size[0]*innerWidth,this.scaleY=this.size[1]*innerHeight,this.mesh.scale.set(this.scaleX,this.scaleY,1)}destroy(){this.addTo.remove(this.object),this.object=null,this.mesh=null,r.killTweensOf(this.tweenObjO),this.outTimer&&(clearTimeout(this.outTimer),this.outTimer=null)}set isActive(e){this._isActive!==e&&(this._isActive=e)}setSizePos(e,t){this.size=e,this.material.uniforms.size.value=this.size,this.pos=t,this.material.uniforms.pos.value=this.pos}resize(e,t){this.scaleX=this.size[0]*innerWidth,this.scaleY=this.size[1]*innerHeight,this.mesh.scale.set(this.scaleX,this.scaleY,1);const i=16/9;let s=1,o=this.scaleY*i;this.scaleX/this.scaleY<i&&(s=o/this.scaleX);let n=1,a=this.scaleY*i;this.scaleX/this.scaleY>i&&(n=a/this.scaleY),this.material.uniforms.ratioMulti.value=[1/s,1/n],this.material.uniforms.scale.value=1.5*Math.random()+.5+1,this.material.uniforms.offset.value=[(2-this.material.uniforms.scale.value)*Math.random(),(2-this.material.uniforms.scale.value)*Math.random()]}show(e){if(!this.visible){this.visible=!0;let t=5;e&&(t=.5),this.animating=!0,r.killTweensOf(this.tweenObjO),r.to(this.tweenObjO,{value:1,duration:t,ease:"Cubic.easeInOut",onComplete:this.onShowComplete.bind(this)})}}onShowComplete(){this.outTimer&&(clearTimeout(this.outTimer),this.outTimer=null);const e=1e3+1e4*Math.random();this.outTimer=setTimeout((()=>{this.hide()}),e),this.animating=!1}hide(e){if(this.visible){this.visible=!1;let t=5;e&&(t=.5),this.animating=!0,r.killTweensOf(this.tweenObjO),r.to(this.tweenObjO,{value:0,duration:t,ease:"Cubic.easeInOut",onComplete:this.onHideComplete.bind(this)})}}onHideComplete(){this.animating=!1,ee("item:out",this.index)}get isActive(){return this._isActive}}const se=B`
    varying vec2 vUv;
    varying vec2 ovUv;
    varying vec3 nPosition;

    uniform sampler2D map;
    uniform float amount;
    uniform float perc;
    uniform float time;
    uniform float ratio;
    uniform float opacity;
    uniform float scale;
    uniform vec2 offset;
    uniform vec2 ratioMulti;


    void main() {

        vec3 nPosition = position;

        vec2 nVuv = uv;
        ovUv = uv;

        vec4 t2 = texture2D(map, ovUv).rgba;

        nVuv.x -= 0.5;
        nVuv.y -= 0.5;

        // nVuv *= vec2(1.0 + (1.0-opacity) * (0.5-distance(vec2(0.0), nVuv)) * 1.0);

        nVuv.x *= ratioMulti.x;
        nVuv.y *= ratioMulti.y;
        nVuv /= vec2(1.0 + scale);
        nVuv.x += offset.x;
        nVuv.y += offset.y;
        nVuv.x += 0.5;
        nVuv.y += 0.5;

        vec4 mvPosition = modelViewMatrix * vec4(nPosition, 1.0);
        gl_Position = projectionMatrix * mvPosition;
        vUv = nVuv;
    }
`,oe=B`
    #include <packing>
    varying vec2 vUv;
    varying vec2 ovUv;
    varying vec3 nPosition;

    uniform sampler2D map;
    uniform float perc;
    uniform float time;
    // uniform float offset;
    uniform float ratio;
    uniform float opacity;
    uniform float stripHeight;
    uniform float xerox;
    uniform float dif;

    uniform vec2 pos;

    // 2D Random
    float random (in vec2 st) {
        return fract(sin(dot(st.xy,
                             vec2(12.9898,78.233)))
                     * 43758.5453123);
    }
    
    // 2D Noise based on Morgan McGuire @morgan3d
    // https://www.shadertoy.com/view/4dS3Wd
    float noise (in vec2 st) {
        vec2 i = floor(st);
        vec2 f = fract(st);
    
        // Four corners in 2D of a tile
        float a = random(i);
        float b = random(i + vec2(1.0, 0.0));
        float c = random(i + vec2(0.0, 1.0));
        float d = random(i + vec2(1.0, 1.0));
    
        // Smooth Interpolation
    
        // Cubic Hermine Curve.  Same as SmoothStep()
        vec2 u = f*f*(3.0-2.0*f);
        // u = smoothstep(0.,1.,f);
    
        // Mix 4 coorners percentages
        return mix(a, b, u.x) +
                (c - a)* u.y * (1.0 - u.x) +
                (d - b) * u.x * u.y;
    }

    vec3 czm_saturation(vec3 rgb, float adjustment)
    {
        // Algorithm from Chapter 16 of OpenGL Shading Language
        const vec3 W = vec3(0.2125, 0.7154, 0.0721);
        vec3 intensity = vec3(dot(rgb, W));
        return mix(intensity, rgb, adjustment);
    }


    void main() {

      vec2 pos = vec2(vUv*2.0);

      vec4 t = texture2D(map, vUv).rgba;

      // Use the noise function
      float n = noise(pos);

        // float amount = (sin(time*0.2 + perc * 5.0)*0.5+0.5) * n;
        float amount = (sin(time*0.2 + perc * 5.0)*0.5+0.5);

        vec2 nVuv = vUv;
        nVuv -= vec2(0.5);
        nVuv.x *= 1.0 + ((nVuv.y*perc)*10.1)*n*t.r;
        nVuv += vec2(0.5);

        vec4 t2 = texture2D(map, nVuv).rgba;

        // vec4 outC = t2 * (1.0 + 0.5) * dAvrg;

        // float multi = sin(ovUv.y * 60.0*stripWidthAmount + ovUv.x * 60.0*stripWidthAmount + time*2.5*offset + perc)*0.5+0.5;
        // float multi2 = sin(ovUv.y * 60.0*stripWidthAmount + ovUv.x * 60.0*stripWidthAmount + 3.1+ time*2.5*offset + perc)*0.5+0.5;
        
        // nVuv += vec2(0.5);

      // t2.rgb = t2.rgb * (1.0-amount)*(nVuv.y) + (vec3(1.0)-t2.rgb) * (amount);
        // t2.r *= float(1.4 + amount);
        // t2.rgb = czm_saturation(t2.rgb, 1.5);
        vec2 novUv = ovUv;
        novUv -= vec2(0.5);
        float alpha = distance(vec2(0.0), novUv);
        novUv += vec2(0.5);

        gl_FragColor = vec4(vec3(t2), (1.0-alpha*2.0) * opacity);
        // gl_FragColor = vec4(1.0, 0.0, 0.0, 1.0);
    }
`;class ne extends i{constructor(){super({vertexShader:se,fragmentShader:oe,transparent:!0,side:0,uniforms:{map:{value:null},time:{value:0},ratio:{value:0},perc:{value:0},ratioMulti:{value:[1,1]},scale:{value:1},dif:{value:0},opacity:{value:0},offset:{value:[1,1]},size:{value:[0,0]},pos:{value:[0,0]}}})}}const ae=new o(1,1,1,1);class re{constructor({addTo:e,texture:i,index:s,total:o}={}){t(this,"tick",((e,t,i,s)=>{this.mesh.position.set(this.pos[0]*innerWidth,this.pos[1]*innerHeight,0),this.material.uniforms.time.value=e,this.material.uniforms.opacity.value=this.tweenObjO.value*s})),this.addTo=e,this.texture=i,this.index=s,this.total=o,this.object=new n,this.addTo.add(this.object),this.tweenObjO={value:0},this.size=[0,0],this.pos=[0,0],this.scale=1,this.material=new ne,this.material.uniforms.map.value=this.texture,this.material.uniforms.perc.value=this.index/this.total,this.material.uniforms.ratio.value=16/9,this.material.uniforms.time.value=1,this.mesh=new a(ae,this.material),this.object.add(this.mesh),this.scaleX=this.size[0]*innerWidth,this.scaleY=this.size[1]*innerHeight,this.mesh.scale.set(this.scaleX,this.scaleY,1)}destroy(){this.addTo.remove(this.object),this.object=null,this.mesh=null,r.killTweensOf(this.tweenObjO),this.outTimer&&(clearTimeout(this.outTimer),this.outTimer=null)}set isActive(e){this._isActive!==e&&(this._isActive=e)}setSizePos(e,t){this.size=e,this.material.uniforms.size.value=this.size,this.pos=t,this.material.uniforms.pos.value=this.pos}resize(e,t){this.scaleX=this.size[0]*innerWidth,this.scaleY=this.size[1]*innerHeight,this.mesh.scale.set(this.scaleX,this.scaleY,1);const i=16/9;let s=1,o=this.scaleY*i;this.scaleX/this.scaleY<i&&(s=o/this.scaleX);let n=1,a=this.scaleY*i;this.scaleX/this.scaleY>i&&(n=a/this.scaleY),this.material.uniforms.ratioMulti.value=[1/s,1/n],this.material.uniforms.scale.value=1.5*Math.random()+.5+1,this.material.uniforms.offset.value=[(2-this.material.uniforms.scale.value)*Math.random(),(2-this.material.uniforms.scale.value)*Math.random()]}show(e){if(!this.visible){this.visible=!0;let t=5;e&&(t=.5),this.animating=!0,r.killTweensOf(this.tweenObjO),r.to(this.tweenObjO,{value:1,duration:t,ease:"Cubic.easeInOut",onComplete:this.onShowComplete.bind(this)})}}onShowComplete(){this.outTimer&&(clearTimeout(this.outTimer),this.outTimer=null);const e=1e3+1e4*Math.random();this.outTimer=setTimeout((()=>{this.hide()}),e),this.animating=!1}hide(e){if(this.visible){this.visible=!1;let t=5;e&&(t=.5),this.animating=!0,r.killTweensOf(this.tweenObjO),r.to(this.tweenObjO,{value:0,duration:t,ease:"Cubic.easeInOut",onComplete:this.onHideComplete.bind(this)})}}onHideComplete(){this.animating=!1,ee("item:out",this.index)}get isActive(){return this._isActive}}const ce=B`
    varying vec2 vUv;

    uniform sampler2D map;

    void main() {

        vec3 nPosition = position;

        vec2 nVuv = uv;
        nVuv.x -= 0.5;
        nVuv.x /= 2.0;
        nVuv.x += 0.25;
        nVuv.x += 0.5;
        vec4 t = texture2D(map, nVuv).rgba;
        float tAvrg = (t.r + t.g + t.b) / 3.0;

        nPosition.z = tAvrg * 1.0;

        vec4 mvPosition = modelViewMatrix * vec4(nPosition, 1.0);
        gl_Position = projectionMatrix * mvPosition;
        vUv = uv;
    }
`,he=B`
    #include <packing>
    varying vec2 vUv;
    uniform sampler2D map;
    uniform sampler2D map2;
    uniform sampler2D stageMap;
    uniform float wolrdToStageProgress;
    uniform float time;
    uniform float ratio;

    uniform float start;
    uniform float stop;
    uniform float speed;
    uniform float contrast;
    uniform float amount;
    uniform float mouseDown;
    uniform vec2 mouse;

    void main() {

        vec4 mA = texture2D(map, vUv).rgba;

        vec2 nVuv = vUv;
        nVuv -= vec2(0.5);
        nVuv.x *= 1.0 - 2.1 * mA.r * distance(nVuv, mouse) * mouseDown;
        nVuv.y *= 1.0 - 2.1 * mA.r * distance(nVuv, mouse) * mouseDown;
        nVuv += vec2(0.5);

        vec4 m = texture2D(map, nVuv).rgba;
        vec4 m2 = texture2D(map2, vUv).rgba;

        // vec4 outC = mix(m, m2, 1.0-m.a);

        // gl_FragColor = vec4(vec3(1.0)-vec3(m.r-m2.r, m.g-m2.g, m.b-m2.b),  1.0);
        

        // gl_FragColor = vec4(vec3(m.r-m2.r, m.g-m2.g, m.b-m2.b),  1.0);
        float mAvrg = (m.r + m.g + m.b)/3.0;
        if (mAvrg < 0.5) {
          mAvrg = smoothstep(0.4, 1.0, mAvrg);
        }

        gl_FragColor = vec4(vec3(m), mAvrg);
    }
`;class le extends i{constructor(){super({vertexShader:ce,fragmentShader:he,transparent:!0,uniforms:{map:{value:null},map2:{value:null},time:{value:0},mouse:{value:[0,0]},ratio:{value:1},mouseDown:{value:0}}})}}const ue=B`
    varying vec2 vUv;

    uniform sampler2D map;

    void main() {

        vec3 nPosition = position;

        vec2 nVuv = uv;
        nVuv.x -= 0.5;
        nVuv.x /= 2.0;
        nVuv.x += 0.25;
        nVuv.x += 0.5;
        vec4 t = texture2D(map, nVuv).rgba;
        float tAvrg = (t.r + t.g + t.b) / 3.0;

        nPosition.z = tAvrg * 1.0;

        vec4 mvPosition = modelViewMatrix * vec4(nPosition, 1.0);
        gl_Position = projectionMatrix * mvPosition;
        vUv = uv;
    }
`,me=B`
    #include <packing>
    varying vec2 vUv;
    uniform sampler2D map;
    uniform sampler2D map2;
    uniform float time;
    uniform float ratio;
    uniform float opacity;
    uniform float color;

    void main() {
        vec4 m = texture2D(map, vUv).rgba;
        vec4 m2 = texture2D(map2, vUv).rgba;

        vec4 outColor = vec4(1.0, 0.6, 0.6, 1.0);
        gl_FragColor = mix(outColor, m, m2.a*opacity);
        
        gl_FragColor = mix(m2, gl_FragColor, m2.a*opacity);
        float colorAvrg = (gl_FragColor.r + gl_FragColor.b + gl_FragColor.g)/3.0;
        gl_FragColor = vec4(vec3(colorAvrg) * vec3(1.0-color) + gl_FragColor.rgb * vec3(color), gl_FragColor.a * (0.4 + color*0.6));
    }
`;class de extends i{constructor(){super({vertexShader:ue,fragmentShader:me,transparent:!0,uniforms:{map:{value:null},map2:{value:null},time:{value:0},opacity:{value:0},ratio:{value:1},color:{value:0}}})}}const ve=B`
    varying vec2 vUvCoords;

    varying vec2 coord;

    uniform sampler2D bufferTexture;
    uniform float time;

    uniform float distort4;

    varying vec3 vPosition;


    void main() {
    vUvCoords = uv;

    coord = uv;

    vec3 newPosition = position;
    // newPosition.x += cos(time*0.01+position.x)*50.0;

    // newPosition.y *= 1.0 + sin(uv.x*0.1)*0.1*-abs(uv.x)*distort4;

    vPosition = newPosition;

    vec4 mvPosition = modelViewMatrix * vec4(newPosition, 1.0);
    
    gl_Position = projectionMatrix * mvPosition;
    }
`,fe=B`
    #include <packing>
    varying vec2 vUvCoords;


    const float PI = 3.141592653589793;
    uniform vec2 center;
    uniform float radius;
    uniform float strength;
    
    uniform float opacity;
    uniform float time;
    uniform float buffer_bufferamount;
    uniform float buffer_xScale;
    
    uniform float displaceamount;
    uniform float displaceavrg;
    
    uniform float hoverAmount;
    
    uniform float distort1;
    uniform float distort2;
    uniform float distort3;
    
    uniform float distortMulti;
    
    varying vec2 coord;
    
    uniform sampler2D bufferTexture;//Our input texture
    
    uniform sampler2D drawingTexture;
    
    uniform vec2 position1;
    uniform vec2 position2;
    uniform float position1Size;
    uniform float position2Size;
    
    uniform vec2 mouse;
    varying vec3 vPosition;
    
    // uniform float PI;
    
    void main() {
    
        vec2 newCoords = vUvCoords;
    
        vec2 newCoords3 = vUvCoords;
        vec4 drawing = texture2D(drawingTexture, newCoords3);
    
        vec2 newCoords2 = vUvCoords;
    
        newCoords2.x -= 0.5;
        newCoords2.y -= 0.5;
    
    
        // newCoords2.y += 0.001;

    
        newCoords2.x += 0.5;
        newCoords2.y += 0.5;
    
        vec4 info = texture2D(bufferTexture, newCoords2);
    
        info *= vec4(0.95);
    
    
        gl_FragColor = vec4(vec3(info),1.0-drawing.a);
        gl_FragColor = mix(gl_FragColor, drawing, drawing.a);

    }
    
`;class pe extends i{constructor(){super({vertexShader:ve,fragmentShader:fe,transparent:!0,uniforms:{bufferTexture:{type:"t",value:null},drawingTexture:{type:"t",value:null},time:{type:"f",value:0},opacity:{type:"f",value:0},hoverAmount:{type:"f",value:0},distort1:{type:"f",value:.4},distort2:{type:"f",value:1},distort3:{type:"f",value:.3},distort4:{type:"f",value:1},distortMulti:{type:"f",value:100},PI:{type:"f",value:Math.PI},avrg:{type:"f",value:0},displaceamount:{type:"f",value:0},displaceavrg:{type:"f",value:0},mouse:{type:"2f",value:[Math.random(),Math.random()]},buffer_bufferamount:{type:"f",value:0},buffer_xScale:{type:"f",value:0}}})}}const we=c({state:()=>({avrg:0,progress:0,muted:!1}),mutations:{setAvrg(e,t){e.avrg=t},setProgress(e,t){e.progress=t},setMute(e,t){e.muted=t}}}),ge=c({state:()=>({bloomtreshold:.69,bloomstrength:.63,bloomradius:.1,audioff:.78,audiorange1:[.3,.94]}),mutations:{setVal(e,t){e[t[0]]=t[1]}}});function be(){const e=h(window.innerWidth),t=h(window.innerHeight),i=m((()=>{e.value=window.innerWidth,t.value=window.innerHeight}),500);return l((()=>{window.addEventListener("resize",i)})),u((()=>{window.removeEventListener("resize",i)})),{wW:e,wH:t}}const ye=new o(1,1),xe={setup(){let{mouse:e,mouseDown:t}=function(){let e=h([0,0]),t=h(!1);const i=t=>{e.value[0]=t.pageX/innerWidth-.5,e.value[1]=t.pageY/innerHeight-.5},s=e=>{t.value=!0},o=e=>{t.value=!1},n=e=>{t.value=!0},a=e=>{t.value=!1},r=t=>{e.value[0]=t.targetTouches[0].clientX/innerWidth-.5,e.value[1]=t.targetTouches[0].clientY/innerHeight-.5};return l((()=>{window.addEventListener("mousemove",i),window.addEventListener("mousedown",s),window.addEventListener("mouseup",o),window.addEventListener("touchstart",n),window.addEventListener("touchend",a),window.addEventListener("touchmove",r)})),u((()=>{window.removeEventListener("mousemove",i),window.removeEventListener("mousedown",s),window.removeEventListener("mouseup",o),window.removeEventListener("touchstart",n),window.removeEventListener("touchend",a),window.removeEventListener("touchmove",r)})),{mouse:e,mouseDown:t}}(),{isMobile:i}=(()=>{const{wW:e}=be();return{isMobile:d((()=>e.value<750))}})(),{wW:s,wH:o}=be();return{mouse:e,mouseDown:t,isMobile:i,wW:s,wH:o}},emits:["ready","click"],props:{scroll:{type:Number}},data:()=>({center:-1,imagesAmount:0,currentIndex:0,videoCanPlay:!1,videoPlaying:!1}),watch:{isMobile(e){},mouseDown(e){e&&this.videoPlaying?this.mouseDownVal=1:this.mouseDownVal=0},windowSize(e){this.onResize()},state:{immediate:!1,handler(e){r.killTweensOf([this.tweenObj1,this.tweenObj2]),this.animating=!0,1===e?(r.to(this.tweenObj1,{value:0,duration:1,onComplete:()=>{this.animating=!1}}),r.to(this.tweenObj2,{value:1,duration:1})):(r.to(this.tweenObj1,{value:1,duration:1,onComplete:()=>{this.animating=!1}}),r.to(this.tweenObj2,{value:0,duration:1}))}}},computed:{audioAvrg:()=>we.state.avrg,audioAffekt:()=>ge.state.audioff,bloomstrength:()=>ge.state.bloomstrength,bloomradius:()=>ge.state.bloomradius,bloomtreshold:()=>ge.state.bloomtreshold,state(){return this.$store.state.track},windowSize(){return[this.wW,this.wH]}},mounted(){this.tweenObj1={value:1},this.tweenObj2={value:0},this.tweenObjC={value:0},this.mode3Vals={bloomtreshold:.64,bloomstrength:1,bloomradius:.64,audioff:.49,audiorange1:[.26,.94]},this.targetTriggerTreshold=0,this.triggerTresHold=0,this.triggered=-1,this.tileSize=21,this.offset=0,this.targetOffset=0,this.prevTargetOffset=0,this.mx=0,this.mxA=0,this.mxTarget=0,this.my=0,this.myA=0,this.myTarget=0,this.mouseDownVal=0,this.mouseDownValT=0,this.targetXRotation=0,this.currentRotationX=5,this.time=0,this.rotationY=0,this.renderer=new p,this.$refs.canvasWrap.appendChild(this.renderer.domElement),this.renderer.setSize(this.wW,this.wH),this.ocamera=new w(this.wW/-2,this.wW/2,this.wH/2,this.wH/-2,1,1e3),this.scene=new g,this.camera=new b(35,this.wW/this.wH,.1,1e3),this.bloomPass=new y(new x(this.wW,this.wH),1.5,.4,.85),this.bloomPass.renderToScreen=!1,this.bloomPass.threshold=.64,this.bloomPass.strength=1,this.bloomPass.radius=.64,this.renderScene=new M(this.scene,this.ocamera),this.composer=new T(this.renderer),this.composer.addPass(this.renderScene),this.composer.addPass(this.bloomPass),this.composer.renderToScreen=!1,this.compScene=new g,this.bloomTarget=new V(this.wW,this.wH),this.drawTarget=new V(this.wW,this.wH),this.drawFinalTarget=new V(this.wW,this.wH),this.compMesh=new a(ye,new le),this.compMesh.material.uniforms.map.value=this.composer.renderTarget2.texture,this.compMesh.material.uniforms.map2.value=this.drawTarget.texture,this.compMesh.position.z=-5,this.compMesh.scale.set(this.wW,this.wH,1),this.compScene.add(this.compMesh),this.bufferScene=new g,this.textureA=new V(this.wW,this.wH,{minFilter:C,magFilter:C,format:D}),this.textureB=new V(this.wW,this.wH,{minFilter:C,magFilter:C,format:D}),this.textureA.texture.wrapS=this.textureA.texture.wrapT=k,this.textureB.texture.wrapS=this.textureB.texture.wrapT=k,this.bufferMaterial=new pe,this.bufferMaterial.uniforms.bufferTexture.value=this.textureB.texture,this.bufferMaterial.uniforms.drawingTexture.value=this.drawFinalTarget.texture,this.bufferObject=new a(ye,this.bufferMaterial),this.bufferObject.position.z=-5,this.bufferObject.scale.set(this.wW,this.wH,1),this.bufferScene.add(this.bufferObject),this.fscene=new g,this.finalMaterial=new de,this.finalMaterial.uniforms.map.value=this.drawTarget.texture,this.finalMaterial.uniforms.map2.value=this.textureB.texture,this.fMesh=new a(ye,this.finalMaterial),this.fMesh.position.z=-5,this.fMesh.scale.set(this.wW,this.wH,1),this.fscene.add(this.fMesh),this.fps=29,this.totalFrames=0,this.video=document.createElement("video"),this.video.className="glvideo",this.video.muted=!0,this.video.src="iStock-1169587514_1.mp4",this.video.preload="auto",this.video.addEventListener("loadedmetadata",this.onMetaDataLoaded),this.video.loop=!0,this.video.setAttribute("webkit-playsinline","webkit-playsinline"),this.video.setAttribute("playsinline","playsinline"),this.video.id="front",this.video.addEventListener("loadedmetadata",this.onVideoLoadedMetaData),this.video.addEventListener("canplay",this.onCanPlay),document.body.appendChild(this.video),this.videoTexture=new z(this.video),this.videoTexture.minFilter=C,this.updateInterval&&(clearInterval(this.updateInterval),this.updateInterval=null),this.updateInterval=setInterval(this.checkVideo,1e3/30),this.stripes=[],this.stripeAmount=30,this.stripeObject=new n,this.scene.add(this.stripeObject),this.stripeObject.position.z=-10;for(let e=0;e<this.stripeAmount;e++){const t=new ie({index:e,texture:this.videoTexture,total:this.stripeAmount,addTo:this.stripeObject});this.stripes.push(t)}for(let e=0;e<this.stripes.length;e++){const t=this.stripes[e],i=[1*Math.random()+.5,1*Math.random()+.5],s=[(1-i[0])*(4.5*Math.random()),(1-i[1])*(3.5*Math.random())];t.setSizePos(i,s),t.resize(this.wW,this.wH),t.show()}this.scene2=new g,this.bloomPass2=new y(new x(this.wW,this.wH),.64,.24,.1),this.bloomPass2.threshold=.64,this.bloomPass2.strength=.24,this.bloomPass2.radius=.1,this.renderScene2=new M(this.scene2,this.ocamera),this.composer2=new T(this.renderer),this.composer2.addPass(this.renderScene2),this.effect2=new A($),this.composer2.addPass(this.effect2),this.composer2.addPass(this.bloomPass2),this.composer2.renderToScreen=!0,this.stripes2=[],this.stripeAmount2=30,this.stripeObject2=new n,this.scene2.add(this.stripeObject2),this.stripeObject2.position.z=-10;for(let e=0;e<this.stripeAmount2;e++){const t=new re({index:e,texture:this.videoTexture,total:this.stripeAmount2,addTo:this.stripeObject2});this.stripes2.push(t)}for(let e=0;e<this.stripes2.length;e++){const t=this.stripes2[e],i=[1*Math.random()+.5,1*Math.random()+.5],s=[(1-i[0])*(4.5*Math.random()),(1-i[1])*(3.5*Math.random())];t.setSizePos(i,s),t.resize(this.wW,this.wH),t.show()}window.addEventListener("click",this.onWClick),window.addEventListener("touchend",this.onWClick),this.animFrame=window.requestAnimationFrame(this.tick),Q("item:out",this.onItemOut),this.onResize()},beforeUnmount(){Z("item:out",this.onItemOut),this.$refs.canvasWrap.removeChild(this.renderer.domElement),window.removeEventListener("click",this.onWClick),window.removeEventListener("touchend",this.onWClick),window.cancelAnimationFrame(this.animFrame),this.updateInterval&&(clearInterval(this.updateInterval),this.updateInterval=null),this.animFrame=null,this.renderer.forceContextLoss(),this.renderer.renderLists.dispose(),this.renderer.dispose(),this.renderer=null,this.bloomTarget.dispose(),this.drawTarget.dispose(),document.body.removeChild(this.video),this.videoTexture.dispose(),this.video.removeEventListener("canplay",this.onCanPlay),this.video.removeEventListener("loadedmetadata",this.onMetaDataLoaded),this.video=null,this.videoBg=null},methods:{onItemOut(e){if(0===this.state)for(let t=0;t<this.stripes.length;t++){const i=this.stripes[t];if(i.index===e){if(this.mouseMoving){const e=[1*Math.random()+.3,1*Math.random()+.3],t=[this.mouse[0]+.2*(Math.random()-.5),-this.mouse[1]+.2*(Math.random()-.5)];i.setSizePos(e,t),i.resize(this.wW,this.wH),i.show(!0)}else{const e=[1*Math.random()+.3,1*Math.random()+.3],t=[(1-e[0])*(1.5*Math.random()),(1-e[1])*(1.5*Math.random())];i.setSizePos(e,t),i.resize(this.wW,this.wH),i.show()}break}}else for(let t=0;t<this.stripes2.length;t++){const i=this.stripes2[t];if(i.index===e){if(this.mouseMoving){const e=[1*Math.random()+.3,1*Math.random()+.3],t=[this.mouse[0]+.2*(Math.random()-.5),-this.mouse[1]+.2*(Math.random()-.5)];i.setSizePos(e,t),i.resize(this.wW,this.wH),i.show(!0)}else{const e=[1*Math.random()+.3,1*Math.random()+.3],t=[(1-e[0])*(1.5*Math.random()),(1-e[1])*(1.5*Math.random())];i.setSizePos(e,t),i.resize(this.wW,this.wH),i.show()}break}}},onWClick(){this.videoPlaying||(this.video.play(),this.videoPlaying=!0,r.to(this.tweenObjC,{duration:1,value:1,ease:"Sine.easeOut"}),this.$emit("ready"))},checkVideo(){this.video.readyState>=this.video.HAVE_CURRENT_DATA&&(this.videoTexture.needsUpdate=!0)},onMetaDataLoaded(){},onCanPlay(){this.videoCanPlay=!0},tick(){if(this.time+=.1,this.mouseDown?(this.mxA=this.mouse[0],this.myA=this.mouse[1]):(this.mxA=0,this.myA=0),this.mxTarget+=.1*(this.mxA-this.mxTarget),this.myTarget+=.1*(this.myA-this.myTarget),Math.abs(this.mouse[0]-this.prevMouseX)>.01||Math.abs(this.mouse[1]-this.prevMouseY)>.01)if(this.mouseMoving=!0,0===this.state)for(let i=0;i<this.stripes.length;i++){const e=this.stripes[i];Math.random()>.7&&!e.animating&&e.hide(!0)}else for(let i=0;i<this.stripes2.length;i++){const e=this.stripes2[i];Math.random()>.7&&!e.animating&&e.hide(!0)}else this.mouseMoving=!1;this.prevMouseX=this.mouse[0],this.prevMouseY=this.mouse[1];const e=this.audioAvrg/255;if(e>.02&&!this.isTriggering&&-1===this.triggered?(this.isTriggering=!0,this.triggered=0,this.triggerTresHold=e):150===this.triggered?(this.triggered=-1,this.triggerTresHold=0):this.isTriggering&&this.triggered>-1?this.triggered+=1:e<.01&&this.isTriggering&&(this.isTriggering=!1),this.targetTriggerTreshold+=.1*(this.triggerTresHold-this.targetTriggerTreshold),this.animating||0===this.state){for(let e=0;e<this.stripes.length;e++){this.stripes[e].tick(this.time,this.mxTarget,this.myTarget,this.tweenObj1.value)}this.renderer.setRenderTarget(this.drawTarget),this.renderer.clear(),this.renderer.render(this.scene,this.ocamera),this.composer.render(),this.renderer.setRenderTarget(this.drawFinalTarget),this.renderer.clear(),this.renderer.render(this.compScene,this.ocamera),this.renderer.setRenderTarget(this.textureB),this.renderer.clear(),this.renderer.render(this.bufferScene,this.ocamera);var t=this.textureA;this.textureA=this.textureB,this.textureB=t,this.bufferMaterial.uniforms.bufferTexture.value=this.textureA.texture,this.finalMaterial.uniforms.opacity.value=this.tweenObj1.value,this.compMesh.material.uniforms.mouse.value=[this.mxTarget,this.myTarget],this.mouseDownValT+=.05*(this.mouseDownVal-this.mouseDownValT),this.compMesh.material.uniforms.mouseDown.value=this.mouseDownValT,this.finalMaterial.uniforms.color.value=this.tweenObjC.value,this.renderer.setRenderTarget(null),this.renderer.clear(),this.renderer.render(this.fscene,this.ocamera)}if(this.animating||1===this.state){for(let e=0;e<this.stripes2.length;e++){this.stripes2[e].tick(this.time,this.mxTarget,this.myTarget,this.tweenObj2.value)}this.effect2.uniforms.mouse.value=[this.mxTarget,this.myTarget],this.mouseDownValT+=.01*(this.mouseDownVal-this.mouseDownValT),this.effect2.uniforms.mouseDown.value=this.mouseDownValT,this.composer2.render()}this.renderer.setClearColor(14518408,this.tweenObj2.value),this.animFrame=window.requestAnimationFrame(this.tick)},onResize(){this.ocamera.left=-this.wW/2,this.ocamera.right=this.wW/2,this.ocamera.top=this.wH/2,this.ocamera.bottom=-this.wH/2,this.ocamera.updateProjectionMatrix(),this.camera.aspect=this.wW/this.wH,this.camera.updateProjectionMatrix(),this.renderer.setSize(this.wW,this.wH),this.composer.setSize(this.wW,this.wH),this.composer2.setSize(this.wW,this.wH),this.bloomTarget.setSize(this.wW,this.wH),this.drawTarget.setSize(this.wW,this.wH),this.drawFinalTarget.setSize(this.wW,this.wH),this.textureA.setSize(this.wW,this.wH),this.textureB.setSize(this.wW,this.wH),this.compMesh.scale.set(this.wW,this.wH,1),this.fMesh.scale.set(this.wW,this.wH,1),this.bufferObject.scale.set(this.wW,this.wH,1)},onDeviceOrientation(e){this.mx=e.gamma/45,this.mxA=Math.max(-.5,Math.min(.5,this.mx))}}},Me=U("data-v-5a809f86");v("data-v-5a809f86");const Te={key:0,class:"click"};f();const Ve=Me(((e,t,i,s,o,n)=>(S(),W(L,null,[O("div",{class:"canvas-wrap",ref:"canvasWrap",onClick:t[1]||(t[1]=t=>e.$emit("click",t))},null,512),O(P,{name:"fade"},{default:Me((()=>[o.videoPlaying?H("",!0):(S(),W("span",Te,"Click to play"))])),_:1})],64))));xe.render=Ve,xe.__scopeId="data-v-5a809f86";const Ce={data:()=>({visible:!1}),mounted(){window.addEventListener("keydown",this.onKeyDown)},beforeUnmount(){window.removeEventListener("keydown",this.onKeyDown)},methods:{onKeyDown(e){"n"===e.key&&(this.visible?this.visible=!1:this.visible=!0)}}},De=U("data-v-140dd65d");v("data-v-140dd65d");const ke={class:"nav"},ze=_('<a href="./" data-v-140dd65d>Video</a><a href="./lines" data-v-140dd65d>Lines</a><a href="./mask" data-v-140dd65d>Mask</a><a href="./mask2" data-v-140dd65d>Mask2</a><a href="./mask3" data-v-140dd65d>Mask3</a><a href="./mask4" data-v-140dd65d>Mask4</a>',6);f();const Ae=De(((e,t,i,s,o,n)=>E((S(),W("div",ke,[ze],512)),[[j,o.visible]])));Ce.render=Ae,Ce.__scopeId="data-v-140dd65d";const Se={data:()=>({anis:[]}),mounted(){for(let e=0;e<this.anis.length;e++){const t=this.anis[e];r.set(t,{y:10,opacity:0}),r.to(t,{y:0,duration:0,opacity:1,ease:"Expo.easeOut",delay:.08*e+.2})}},beforeUnmount(){},beforeUpdate(){this.anis=[]},updated(){console.log(this.anis),console.log("updated")},methods:{setAni(e){e&&this.anis.push(e)}}},We=U("data-v-0603b6de");v("data-v-0603b6de");const Oe={class:"menu"},He={class:"block"},Pe={class:"block"},Le={class:"bottom"};f();const Ue=We(((e,t,i,s,o,n)=>(S(),W("div",Oe,[O("span",{class:"menu-btn hover",onClick:t[1]||(t[1]=t=>e.$emit("close"))},"X"),O("div",He,[O("h3",{ref:n.setAni},"Audio visual releases",512),O("a",{class:"hover",ref:n.setAni,href:"http://streettalk.holodec.world"},"Street talk",512),O("a",{class:"hover",ref:n.setAni,href:"http://drunk.holodec.world"},"Drunk off that liqa",512)]),O("div",Pe,[O("h3",{ref:n.setAni},"Links",512),O("a",{class:"hover",ref:n.setAni,target:"_blank",href:"https://saga-pro.co"},"Saga Productions",512),O("a",{class:"hover",ref:n.setAni,target:"_blank",href:"https://www.instagram.com/holodec_"},"Instagram",512),O("a",{class:"hover",ref:n.setAni,target:"_blank",href:"https://holodec.bandcamp.com/"},"Bandcamp",512),O("a",{class:"hover",ref:n.setAni,target:"_blank",href:"https://timetablerecords.com"},"Timetable Records",512)]),O("div",Le,[O("h3",{ref:n.setAni},"Site by ",512),O("a",{class:"hover",ref:n.setAni,target:"_blank",href:"https://bureau.cool"},"Bureau Cool",512)])]))));Se.render=Ue,Se.__scopeId="data-v-0603b6de";const Ee={props:{index:{type:Number,required:!0},offsetLeft:{type:Number,required:!0},width:{type:Number,required:!0},storeValName:{type:String},name:{type:String},initialVal:{type:Number,default:1}},data:()=>({val:-1,mouseDown:!1}),computed:{dispVal(){return-1!==this.val?this.val:this.initialVal}},watch:{val:{handler(e){ge.commit("setVal",[this.storeValName,this.dispVal])}}},mounted(){window.addEventListener("mousemove",this.onWMouseMove),window.addEventListener("mouseup",this.onWMouseUp),window.addEventListener("touchmove",this.onWTouchMove),window.addEventListener("touchend",this.onWTouchEnd),this.mx=0,this.my=0},beforeUnmount(){window.removeEventListener("mousemove",this.onMouseMove),window.removeEventListener("mouseup",this.onWMouseUp),window.removeEventListener("touchmove",this.onWTouchMove),window.removeEventListener("touchend",this.onWTouchEnd)},methods:{onMouseDown(e){this.mx=e.clientX,this.my=e.clientY,this.mouseDown=!0},onWMouseMove(e){this.mx=e.clientX,this.my=e.clientY,this.mouseDown&&(this.val=(this.mx-this.offsetLeft)/(this.width-2))},onWMouseUp(e){this.mx=e.clientX,this.my=e.clientY,this.mouseDown&&(this.val>1?this.val=1:this.val<0&&(this.val=0),this.mouseDown=!1)},onTouchStart(e){this.mx=e.touches[0].clientX,this.my=e.touches[0].clientY,this.mouseDown=!0},onWTouchMove(e){this.mx=e.touches[0].clientX,this.my=e.touches[0].clientY,this.mouseDown&&(this.val=(this.mx-this.offsetLeft)/(this.width-2))},onWTouchEnd(e){this.mx=e.changedTouches[0].clientX,this.my=e.changedTouches[0].clientY,this.val>1?this.val=1:this.val<0&&(this.val=0),this.mouseDown=!1}}},je={key:0,class:"name"},_e={class:"val-disp"},Fe={class:"c-inner"},Ye=O("div",{class:"bg"},null,-1);Ee.render=function(e,t,i,s,o,n){return S(),W("div",{class:["slider",{down:o.mouseDown}],onMousedown:t[1]||(t[1]=(...e)=>n.onMouseDown&&n.onMouseDown(...e)),onTouchstart:t[2]||(t[2]=(...e)=>n.onTouchStart&&n.onTouchStart(...e))},[""!==i.name?(S(),W("span",je,F(i.name),1)):H("",!0),O("div",_e,F(n.dispVal.toFixed(2)),1),O("div",Fe,[O("div",{class:"val",style:`transform: scale3d(${n.dispVal}, 1, 1);`},null,4),Ye])],34)};const Xe={props:{index:{type:Number,required:!0},offsetLeft:{type:Number,required:!0},width:{type:Number,required:!0},storeValName:{type:String},name:{type:String},initialVal:{type:Array,default:[0,1]}},data:()=>({val:-1,val2:-1,mouseDown:!1}),watch:{val:{handler(e){ge.commit("setVal",[this.storeValName,this.dispVal,,])}},val2:{handler(e){ge.commit("setVal",[this.storeValName,this.dispVal])}}},computed:{scaleVal(){return this.dispVal[1]-this.dispVal[0]},dispVal(){let e=this.initialVal;return-1!==this.val&&(e[0]=this.val),-1!==this.val2&&(e[1]=this.val2),e}},mounted(){window.addEventListener("mousemove",this.onWMouseMove),window.addEventListener("mouseup",this.onWMouseUp),window.addEventListener("touchmove",this.onWTouchMove),window.addEventListener("touchend",this.onWTouchEnd),this.mx=0,this.my=0},beforeUnmount(){window.removeEventListener("mousemove",this.onMouseMove),window.removeEventListener("mouseup",this.onWMouseUp),window.removeEventListener("touchmove",this.onWTouchMove),window.removeEventListener("touchend",this.onWTouchEnd)},methods:{onHandleMouseDown(e){this.mx=e.clientX,this.my=e.clientY,this.mouseDownHandle=!0},onHandle2MouseDown(e){this.mx=e.clientX,this.my=e.clientY,this.mouseDownHandle2=!0},onWMouseMove(e){this.mx=e.clientX,this.my=e.clientY,this.mouseDownHandle?this.val=(this.mx-this.offsetLeft)/(this.width-2):this.mouseDownHandle2&&(this.val2=(this.mx-this.offsetLeft)/(this.width-2))},onWMouseUp(e){this.mx=e.clientX,this.my=e.clientY,this.mouseDownHandle?(this.val>1?this.val=1:this.val<0&&(this.val=0),this.mouseDownHandle=!1):this.mouseDownHandle2&&(this.val2>1?this.val2=1:this.val2<0&&(this.val2=0),this.mouseDownHandle2=!1)},onHandleTouchStart(e){this.mx=e.touches[0].clientX,this.my=e.touches[0].clientY,this.mouseDownHandle=!0},onHandle2TouchStart(e){this.mx=e.touches[0].clientX,this.my=e.touches[0].clientY,this.mouseDownHandle2=!0},onWTouchMove(e){this.mx=e.touches[0].clientX,this.my=e.touches[0].clientY,this.mouseDownHandle?this.val=(this.mx-this.offsetLeft)/(this.width-2):this.mouseDownHandle2&&(this.val2=(this.mx-this.offsetLeft)/(this.width-2))},onWTouchEnd(e){this.mx=e.changedTouches[0].clientX,this.my=e.changedTouches[0].clientY,this.mouseDownHandle?(this.val>1?this.val=1:this.val<0&&(this.val=0),this.mouseDownHandle=!1):this.mouseDownHandle2&&(this.val2>1?this.val2=1:this.val2<0&&(this.val2=0),this.mouseDownHandle2=!1)}}},Re={key:0,class:"name"},Ie={class:"val-disp"},Ne={class:"val-disp2"},$e={class:"c-inner"},Be=O("div",{class:"bg"},null,-1);Xe.render=function(e,t,i,s,o,n){return S(),W("div",{class:["range",{down:o.mouseDown}]},[""!==i.name?(S(),W("span",Re,F(i.name),1)):H("",!0),O("div",Ie,F(n.dispVal[0].toFixed(2)),1),O("div",Ne,F(n.dispVal[1].toFixed(2)),1),O("div",$e,[O("div",{class:"handle h",style:`transform: translate3d(\n          ${n.dispVal[0]*i.width}px\n        , 0, 0);`,onMousedown:t[1]||(t[1]=(...e)=>n.onHandleMouseDown&&n.onHandleMouseDown(...e)),onTouchstart:t[2]||(t[2]=(...e)=>n.onHandleTouchStart&&n.onHandleTouchStart(...e))},null,36),O("div",{class:"handle2 h",style:`transform: translate3d(\n          ${n.dispVal[1]*i.width}px\n        , 0, 0);`,onMousedown:t[3]||(t[3]=(...e)=>n.onHandle2MouseDown&&n.onHandle2MouseDown(...e)),onTouchstart:t[4]||(t[4]=(...e)=>n.onHandle2TouchStart&&n.onHandle2TouchStart(...e))},null,36),O("div",{class:"val",style:`transform: translate3d(${n.dispVal[0]*i.width}px, 0, 0) scale3d(${n.scaleVal}, 1, 1);`},null,4),Be])],2)};const qe={components:{Slider:Ee,Range:Xe},data:()=>({offsetLeft:0,width:0,visible:!1}),computed:{bloomTreshold:()=>ge.state.bloomtreshold,bloomRadius:()=>ge.state.bloomradius,bloomStrength:()=>ge.state.bloomstrength,audioAffekt:()=>ge.state.audioff,audioRange:()=>ge.state.audiorange1},mounted(){window.addEventListener("resize",this.onResize),window.addEventListener("keydown",this.onKeyDown),this.onResize(),this.$nextTick((()=>{this.onResize()}))},beforeUnmount(){window.removeEventListener("resize",this.onResize),window.removeEventListener("keydown",this.onKeyDown)},methods:{onKeyDown(e){"c"===e.key&&this.toggleVisibility()},toggleVisibility(){this.visible?this.visible=!1:this.visible=!0},onResize(){const e=this.$el.offsetLeft;e&&(this.offsetLeft=e),this.width=.3*innerWidth,this.width<200&&(this.width=200)}}};qe.render=function(e,t,i,s,o,n){const a=Y("Slider"),r=Y("Range");return S(),W("div",{class:["controls",{visible:o.visible}],style:`width: ${o.width}px;`},[O(a,{index:1,"offset-left":o.offsetLeft,width:o.width,storeValName:"bloomtreshold",name:"Bloom Treshold",initialVal:n.bloomTreshold},null,8,["offset-left","width","initialVal"]),O(a,{index:2,"offset-left":o.offsetLeft,width:o.width,storeValName:"bloomstrength",name:"Bloom Strength",initialVal:n.bloomStrength},null,8,["offset-left","width","initialVal"]),O(a,{index:3,"offset-left":o.offsetLeft,width:o.width,storeValName:"bloomradius",name:"Bloom Radius",initialVal:n.bloomRadius},null,8,["offset-left","width","initialVal"]),O(a,{index:4,"offset-left":o.offsetLeft,width:o.width,storeValName:"audioff",name:"Audio Affekt",initialVal:n.audioAffekt},null,8,["offset-left","width","initialVal"]),O(r,{index:5,"offset-left":o.offsetLeft,width:o.width,storeValName:"audiorange1",name:"Audio Affekt Range",initialVal:n.audioRange},null,8,["offset-left","width","initialVal"])],6)};const Ke={setup(){const{isIOS:e}={isIOS:["iPad Simulator","iPhone Simulator","iPod Simulator","iPad","iPhone","iPod"].includes(navigator.platform)||navigator.userAgent.includes("Mac")&&"ontouchend"in document};return console.log(e),{isIOS:e}},components:{},props:{src:{type:String}},data:()=>({playClicked:!1}),computed:{audiorange1:()=>ge.state.audiorange1,fftamount(){return this.analyser?this.analyser.frequencyBinCount:1},avrgEdgeLow(){return this.audiorange1[0]*this.fftamount},avrgEdgeHight(){return this.audiorange1[1]*this.fftamount}},watch:{src(e){e&&this.audio&&(this.audio.src=e,this.audio.play())}},mounted(){this.src||(this.src="/someway.mp3"),this.audio=new Audio,this.audio.src=this.src,this.audio.loop=!0,this.audio.volume=1,window.addEventListener("click",this.onWClick),this.animFrame=window.requestAnimationFrame(this.tick)},beforeUnmount(){window.removeEventListener("click",this.onWClick),window.cancelAnimationFrame(this.animFrame),this.audio.pause(),this.audio.removeAttr("src"),this.audio=null,this.analyser=null,this.context=null,this.source=null},methods:{tick(){if(this.analyser){var e=new Uint8Array(this.analyser.frequencyBinCount);this.analyser.getByteFrequencyData(e),this.currentData=e;for(var t=0,i=0;i<this.currentData.length;i++)i>=this.avrgEdgeLow&&i<=this.avrgEdgeHight&&(t+=this.currentData[i]);t/=this.avrgEdgeHight-this.avrgEdgeLow,we.commit("setAvrg",t)}this.audio.duration&&(this.progress=this.audio.currentTime/this.audio.duration,we.commit("setProgress",this.progress)),this.animFrame=window.requestAnimationFrame(this.tick)},onWClick(){this.playClicked||(window.removeEventListener("click",this.onWClick),this.playClicked=!0,this.audio.play())}}};Ke.render=function(e,t,i,s,o,n){return null};const Ge={components:{GL:xe,Nav:Ce,Controls:qe,AudioAnalyzer:Ke,Menu:Se},data:()=>({scrollY:0,ready:!1,menuOpen:!1,uiVisible:!0}),computed:{muted(){return this.$store.state.muted}},watch:{menuOpen:{handler(e,t){}}},mounted(){this.scrollY=0,window.addEventListener("resize",this.onResize),window.addEventListener("keyup",this.onKeyUp),this.$nextTick((()=>{this.onResize()}))},beforeUnmount(){window.removeEventListener("resize",this.onResize),window.removeEventListener("keyup",this.onKeyUp)},methods:{onKeyUp(e){"s"===e.key&&this.$store.commit("setTrack",1)},onResize(e){},onMuteClick(){this.muted?this.$store.commit("setMute",!1):this.$store.commit("setMute",!0)}}},Je={class:"topleft"},Qe={key:0,class:"top left"},Ze=O("h1",null,"Holodec",-1),et=O("svg",{x:"0px",y:"0px",viewBox:"0 0 3912 132"},[O("path",{class:"st0",d:"M1304,132c-169,0-338,0-507,0c0.6-0.4,1.2-1.1,1.8-1.2c5.8-1.4,14.4-11.2,14.6-17c0.2-8.3-0.4-16.7,0.4-25\n    c0.7-7.7-6.8-12.2-12.8-12.4c-8.7-0.2-17.5-0.4-26,0.7c-3.3,0.4-8.9-0.4-9.8,5.4c-1,5.7-2.2,11.4-2.7,17.2c-0.6,6.6-3,11.8-9.3,14.8\n    c-3,1.5-6.7,2.9-8.4,5.4c-2.5,3.8-2.3,8.4,1.3,12c-7.7,0-15.3,0-23,0c2.8-1.9,6.3-3,6.3-7.5c0-5.1-3.7-6.6-7.2-8.4\n    c-4.7-2.4-9.1-5.1-10.3-10.5c-1.4-6.6-2-13.4-3.2-20.1c-0.4-2.1-1.2-5.1-2.8-5.8c-3.3-1.6-7.1-2.6-10.7-2.8\n    c-4.8-0.3-9.6,0.3-14.4,0.4c-4.7,0.1-9.4-0.4-14,0.2c-2,0.3-3.7,2.6-5.4,4.2c-3.8,3.8-2.8,8.7-2.8,13.3c0,6.6-0.4,13.3,1.1,19.6\n    c1.4,6.1,2.8,13.4,11.2,14.8c0.5,0.1,0.8,1.7,1.2,2.6c-175.3,0-350.7,0-526,0c2.2-3.2,5.8-6.2,6.3-9.7c1-7.3,0.5-14.8,0.3-22.2\n    c-0.1-4.9-0.6-9.8-0.9-14.7c-0.4-5.7-3.9-8.1-9.3-8.2c-9.7-0.2-19.4-0.5-29.1-0.3c-5.4,0.1-9.4,5.3-9.6,10.9\n    c-0.3,7.1-1.2,14.1-1.3,21.1c-0.1,7.7-1.4,16.1,6.9,21.2c0.4,0.3,0.4,1.2,0.6,1.8c-23,0-46,0-69,0c5.6-1.6,7.2-6.6,8.4-11.2\n    c1-3.7,0.2-7.8,0.2-11.7c0-7,0.1-14-0.1-21c0-1.4-0.4-3-1.1-4.2c-3.2-5.3-7.5-7.5-14.1-7C22.8,77.8,10.9,75.1,0,81\n    c0-7.7,0-15.3,0-23c3,7.5,9.3,8.1,16.1,7.7c1.8-0.1,3.6-0.1,5.3-0.1c13.2-0.1,26.5,0,39.7-0.5c7.3-0.3,11.8-9.6,7.9-15.7\n    c-0.8-1.3-2.4-2.3-3.8-2.9c-2.9-1.1-6.1-1.4-8.9-2.6c-2-0.9-4.7-2.4-5-4.1c-1.1-5-1.3-10.3-1.7-15.5c-0.5-6.6,1.9-13.6-3-19.4\n    c-1.4-1.7-3-3.2-4.5-4.8c22.7,0,45.3,0,68,0c-0.3,0.9-0.3,2.1-0.9,2.7c-1.6,1.8-4.5,3.2-4.9,5.2c-1.1,6.1-2.4,12.5-1.5,18.4\n    c0.7,4.3-0.8,7.9-0.9,11.8c-0.1,1.5-2.3,3.4-4,4.3c-3,1.6-6.4,2.5-9.6,4c-6,2.8-7.3,5.5-5.7,11.9c0.8,3.5,4.3,8.1,10.2,7.4\n    c4.7-0.5,9.5-0.5,14.2-0.5c10.7,0.1,21.4,0.6,32,0.2c3.8-0.1,9-1.3,10.9-3.9c2.6-3.7,3.5-9.2,3.7-14c0.4-7.8-0.5-15.7-0.7-23.5\n    c-0.2-7.7,1.7-15.9-4.8-22.2c-0.3-0.3,0.1-1.3,0.1-1.9c176,0,352,0,528,0c-0.3,0.6-0.6,1.7-0.9,1.7c-8.3,0.7-13,6.1-15.3,13.2\n    c-1.4,4.5-0.9,9.6-1.1,14.5c-0.2,7.8-0.6,15.7-0.3,23.5c0.3,9.8,3.8,12.5,13.3,12.3c8.8-0.2,17.6,0.4,26.3,0.4\n    c4.7,0,10.2-3.4,10.5-6.7c0.4-5.4,0.2-10.8,0.5-16.1c0.2-4.8-0.1-9.8,1.1-14.3c1.8-6.5,6.6-10.8,12.6-13.9c8.6-4.4,9.1-8.2,2.3-14.7\n    c7,0,14,0,21,0c-4.9,8.4-4,11,4.8,15.7c7.5,4,11.5,10.6,12,19c0.4,6.9,0.2,13.8,0.6,20.7c0.2,5,4.8,10.1,9,10.2\n    c10.6,0.1,21.2,0.1,31.8,0c3.9,0,9.9-5.1,9.8-8.2c-0.1-12.7,0.1-25.4-0.5-38c-0.2-3.5-2.3-7-4-10.3c-2.6-5-7.5-7.2-12.5-9.1\n    c169.3,0,338.7,0,508,0c-1.1,1-1.9,2.3-3.2,2.8c-4.3,1.8-5.2,5.1-5.1,9.4c0.5,14.4,0.8,28.7,1.1,43.1c0.2,6.9,3.7,10.6,10.6,10.4\n    c8.6-0.2,17.2-0.6,25.8-1c5.7-0.3,8.9-3.7,9-9.5c0.1-10.6-0.4-21.3,0.3-31.9c0.6-9,0.3-17.3-7.6-23.3c185,0,370,0,555,0\n    c-0.8,0.7-1.6,1.7-2.5,2.1c-6.1,2.4-10.6,6.3-12.1,12.8c-1,4.2-1.9,8.6-2,13c-0.2,5.6,1.4,11.5,0.5,16.9c-1,5.5,0.2,10.3,1.4,15.4\n    c0.9,3.9,3.3,5.3,6.6,5.4c9.9,0.2,19.8,0.5,29.7-0.1c4.4-0.3,10.4,0.3,11.8-5.8c1.1-4.4,0.8-9.2,1.1-13.8c0.4-5.8,0.2-11.6,1.2-17.3\n    c1.1-6.5,5.9-10.2,11.5-13.2c8.7-4.7,9.3-8.6,2.8-15.4c7.3,0,14.7,0,22,0c-6,6.6-4.7,10.7,3.3,15.5c4.2,2.5,9.4,6.4,10.4,10.5\n    c2.2,8.7,2.5,18,2.4,27c-0.1,6.8,3.6,12.4,10.5,12.6c7.7,0.2,15.3,0.1,23,0c3.6,0,7.4,0.6,10.6-0.6c4.5-1.7,6.2-6.3,6.4-10.8\n    c0.3-10.6,0.6-21.1-0.1-31.7c-0.5-8.6-2.5-17-12.9-19.6c-1.4-0.3-2.4-2-3.6-3c176.7,0,353.3,0,530,0c-4.9,2.9-7.2,7.4-7.4,12.8\n    c-0.2,6.7-0.1,13.5-0.1,20.2c0,5,0.4,10.1-0.1,15.1c-1.1,12.6,3.7,17.9,16.5,17.5c7-0.2,14.1,0.1,21.1-0.1c7.8-0.2,12.7-3.3,13.1-12\n    c0.5-11.1,0.4-22.3,0.5-33.4c0-2.7-0.2-5.5,0-8.2c0.5-6.5-4.9-8.6-8.7-12c8.7,0,17.3,0,26,0c-0.4,0.5-0.7,1-1.2,1.4\n    c-2.3,2-5.7,4.2-3.6,7.4c1.5,2.3,4.8,4.2,7.6,4.8c5.4,1.2,11.1,1.3,16.6,2.1c7.6,1.1,16.2,10.5,16.3,17.5c0.2,7.7,0.3,15.5,0.6,23.2\n    c0.3,6.3,3.1,8.7,9.5,8.6c8.8-0.1,17.6-0.3,26.4-0.1c10.8,0.2,15.4-4.6,14.3-15.3c-0.3-2.7,0-5.4-0.1-8.1\n    c-0.3-7.9,0.6-16.1-1.2-23.6c-1.7-7.1-5.8-13.7-14.7-14.9c-2.6-0.3-5.1-2-7.6-3c171.7,0,343.3,0,515,0c-0.8,0.4-1.6,1.2-2.4,1.3\n    c-5.4,0.3-7.5,3.5-7.9,8.4c-0.3,4-0.4,8-0.3,12.1c0.2,10.8,0.5,21.7,0.9,32.5c0.3,8.6,3.4,11.5,11.4,11.4c8.6-0.1,17.1-0.8,25.7-0.6\n    c8.9,0.2,12.2-2.6,12.2-11.5c0-12.2-0.2-24.3,0.1-36.5c0.1-6.8-0.2-13.1-6.7-17c8.3,0,16.7,0,25,0c-0.7,0.6-1.4,1.2-2.1,1.7\n    c-4.5,3.3-6.4,8.9-4.5,13.2c2.7,6,7,7.9,13.5,6.9c3-0.5,6.1,0.4,9.2,0.1c5.8-0.5,12,2.4,17.1-4.1c2.4-3.1,2.9-5.5,3-8.8\n    c0.1-4.2-2.6-6-5.9-7.6c-0.8-0.4-1.5-1-2.2-1.5c169.3,0,338.7,0,508,0c-0.3,0.4-0.5,1-0.9,1.1c-11.2,2-18.3,13.2-18,23.1\n    c0.4,10.4-0.2,20.9-0.4,31.3c-0.1,2.8,5.3,9.3,8.2,9.4c11.1,0.4,22.1,0.6,33.2,0.7c3.8,0,9.1-4.6,9.2-7.9c0.1-5.4-0.1-10.9-0.1-16.3\n    c-0.1-10.7,2.5-19.9,12.8-25.7c8-4.5,8.7-8.5,3.9-15.7c7.3,0,14.7,0,22,0c-0.5,0.9-1,1.8-1.5,2.6c-1.7,2.6-4.4,5.2-1.3,8.4\n    c1.6,1.6,3.3,3.3,5.3,4.3c5,2.5,8.9,5.7,11.2,11c2.2,4.9,6.1,8,11.8,8.3c3.6,0.2,7.2,1.3,10.8,1c10-0.9,20.9,2.1,29.7-5.5\n    c0,21.7,0,43.3,0,65c-0.8-0.2-2.1-0.1-2.2-0.5c-1.8-5.8-6.8-3.8-10.7-4.4c-1.8-0.3-3.6-0.2-5.4-0.2c-7,0-14-0.6-20.9,0.2\n    c-4.4,0.5-10,0.8-11.4,7c-0.6,2.8-1.5,5.5-2.3,8.3c-1.7,6.4-7,9-12.1,11.9c-6.1,3.4-6.6,7-1.8,11.7c0.6,0.6,0.5,2,0.7,3.1\n    c-7.3,0-14.7,0-22,0c0.8-2.1,1.3-4.4,2.5-6.3c2.1-3.2,0.3-5.2-1.8-6.8c-3.3-2.4-7.1-4.3-10.6-6.5c-0.9-0.5-1.9-1.2-2.3-2\n    c-3.9-7.6-4.3-15.8-4.3-24.1c0-4.8-2.5-7.7-7.2-8.2c-1.4-0.2-2.9-0.5-4.3-0.7c-11.3-1.5-22.5-2-33.6,1.4c-1.5,0.5-3.5,1.8-3.8,3.1\n    c-1,3.5-1.6,7.2-1.7,10.9c-0.1,5.2,1,10.4,0.4,15.5c-1,8.8,6.9,19.1,14.1,21.8c2.2,0.8,4.5,1.3,6.8,1.9c-171.3,0-342.7,0-514,0\n    c1.4-0.6,2.8-1.8,4.1-1.7c4.9,0.5,7-3.5,8.2-6.4c2.4-6-3.2-13.6-10-13.9c-8.5-0.4-17-0.2-25.5,0.3c-5.2,0.3-8.3,5.8-7.8,11.1\n    c0.5,5.4,3.6,8.4,7.9,10.7c-9,0-18,0-27,0c4.4-2.7,7.8-6,7.7-11.7c-0.1-11,0-22.1-0.1-33.1c-0.1-5-4.2-9.7-8.8-9.8\n    c-9.1-0.2-18.1-0.4-27.2-0.2c-9.7,0.2-15.9,6.1-14,15.3c2.2,10.5-1.3,20.9,1.7,31.5c1.2,4.2,2.2,5.8,5.8,6.7c1,0.3,1.9,0.9,2.9,1.3\n    c-172.3,0-344.7,0-517,0c0.5-0.4,0.9-1,1.4-1.2c3.7-1.1,8.2-1.2,11.1-3.3c4.2-3,9.4-7,10.6-11.5c2.2-8.1,1.9-17,2.5-25.6\n    c0.6-8.5-3.1-12.9-13.9-13.6c-7.7-0.5-15.4-0.5-23.2-0.6c-6.1-0.1-12.2,2.9-13,6.8c-0.7,3.9-1.1,7.8-1.2,11.8\n    c-0.4,11.2-5.8,18.8-17.4,20.4c-5.5,0.8-11,1.2-16.5,2.1c-3.3,0.5-6.6,1.5-6.1,6.1c0.4,4,1.3,7.2,6.3,7.1c0.8,0,1.6,0.8,2.4,1.2\n    c-10,0-20,0-30,0c2.7-3.2,7.5-6.3,7.8-9.7c1.1-11.5,1-23.2,0.5-34.8c-0.1-3.8-1.3-8.6-6.7-9.2c-4.9-0.6-9.7-0.8-14.6-1.1\n    c-4.2-0.3-8.4-0.9-12.5-0.5c-5.5,0.5-12.1-0.5-14.7,6.4c-1.2,3.2-2.3,6.6-2.3,10c-0.1,9.4,0.4,18.7,0.7,28.1\n    c0.1,3.3,0.6,6.2,4.2,7.7c0.8,0.4,1.1,2,1.7,3.1c-177.3,0-354.7,0-532,0c1-0.7,1.9-1.9,3-2.1c8.1-1.3,14.9-7.4,15.5-15.2\n    c0.7-9.1,1.1-18.2,0.9-27.3c-0.1-2.6-2.1-6.1-4.3-7.6c-2.5-1.7-6.3-2.1-9.5-2.1c-9.7-0.2-19.4-0.3-29,0.3c-2.6,0.2-6.5,1-6.9,5.2\n    c-0.5,5-1.1,10-1.3,15.1c-0.3,7.9-3.1,13.7-11,16.8c-2.9,1.2-5.5,4.1-7.5,6.8c-3.1,4.4,0.3,7.4,3.1,10.2c-7.3,0-14.7,0-22,0\n    c2.4-3.1,6.7-5.7,3.6-10.4c-1.4-2.1-4-3.5-6.1-5.2c-3-2.4-6.3-4.4-8.9-7.2c-5-5.3-3.3-12.3-4.2-18.6c-0.3-2.3,0.1-5-0.9-6.9\n    c-2.8-5.2-10.6-7.9-16.5-7.3c-6.4,0.6-13,0.8-19.4,0.2c-6.9-0.6-16.1,5.1-15,10.9c1.4,7.4-0.6,14.8,0.6,22c0.8,4.9,3.1,9.9,5.8,14.2\n    c2.1,3.3,6,5.6,9.1,8.3c-166.7,0-333.3,0-500,0c1-0.4,1.9-1.1,2.9-1.3c5.9-0.9,9.4-4.8,9.3-10.6c0-4.8-4.7-9.6-9.9-9.8\n    c-8.6-0.4-17.3-0.7-25.9-0.8c-2.3,0-5.7,0.4-6.6,1.8c-2,3.3-4,7.5-3.8,11.2c0.2,3.8,1.7,8.8,7.9,7.8c0.6-0.1,1.4,1.1,2.1,1.7\n    c-10,0-20,0-30,0c8.1-2.8,9-9.2,8.7-16.5c-0.4-9.1-0.3-18.1-0.6-27.2c-0.2-6.3-2.8-10.2-8.9-10.9c-7.3-0.8-14.7-0.6-22.1-0.6\n    c-11.2,0-15.2,4.7-15.6,15.3c-0.3,7-0.5,14.1,0,21C1296.9,120.1,1296.4,127.8,1304,132z M3254.9,65.7c0,0.3,0.1,0.6,0.1,0.8\n    c4.9-0.9,9.8-2.2,14.8-2.5c5.8-0.4,6-5.3,6.4-8.6c0.5-4.2-1.6-8.4-6.6-9.6c-10.3-2.6-20.7-1.9-30.8,0.3c-5.7,1.2-8.2,12-3.7,15.5\n    c2.7,2.1,6.5,3.3,9.9,4C3248.2,66.2,3251.6,65.7,3254.9,65.7z"}),O("path",{d:"M2667,0c2.5,1,5,2.7,7.6,3c8.9,1.2,13,7.8,14.7,14.9c1.8,7.5,0.9,15.7,1.2,23.6c0.1,2.7-0.2,5.4,0.1,8.1\n    c1,10.8-3.5,15.5-14.3,15.3c-8.8-0.2-17.6,0-26.4,0.1c-6.4,0.1-9.2-2.3-9.5-8.6c-0.3-7.7-0.5-15.4-0.6-23.2\n    c-0.1-7-8.7-16.4-16.3-17.5c-5.5-0.8-11.2-0.9-16.6-2.1c-2.8-0.6-6.1-2.5-7.6-4.8c-2-3.2,1.4-5.4,3.6-7.4c0.5-0.4,0.8-0.9,1.2-1.4\n    C2625,0,2646,0,2667,0z"}),O("path",{d:"M148,0c-0.1,0.7-0.4,1.6-0.1,1.9c6.5,6.4,4.6,14.5,4.8,22.2c0.2,7.8,1.1,15.7,0.7,23.5c-0.2,4.8-1.1,10.2-3.7,14\n    c-1.8,2.6-7.1,3.7-10.9,3.9c-10.7,0.4-21.3-0.2-32-0.2c-4.7,0-9.5,0-14.2,0.5c-5.9,0.6-9.3-4-10.2-7.4c-1.6-6.5-0.2-9.1,5.7-11.9\n    c3.1-1.5,6.5-2.3,9.6-4c1.7-0.9,4-2.8,4-4.3c0.1-3.9,1.6-7.5,0.9-11.8c-0.9-5.9,0.3-12.4,1.5-18.4c0.4-2,3.3-3.4,4.9-5.2\n    c0.6-0.7,0.6-1.8,0.9-2.7C122.7,0,135.3,0,148,0z"}),O("path",{d:"M42,0c1.5,1.6,3.1,3.1,4.5,4.8c4.9,5.9,2.5,12.9,3,19.4c0.4,5.2,0.6,10.4,1.7,15.5c0.4,1.7,3.1,3.3,5,4.1\n    c2.8,1.2,6,1.5,8.9,2.6c1.5,0.5,3,1.6,3.8,2.9c3.9,6.1-0.6,15.4-7.9,15.7c-13.2,0.5-26.5,0.4-39.7,0.5c-1.8,0-3.6,0-5.3,0.1\n    C9.3,66.1,3,65.5,0,58C0,38.8,0,19.5,0,0C14,0,28,0,42,0z"}),O("path",{d:"M1941,0c6.5,6.8,6,10.8-2.8,15.4c-5.6,3-10.4,6.7-11.5,13.2c-1,5.7-0.8,11.5-1.2,17.3c-0.3,4.6-0.1,9.4-1.1,13.8\n    c-1.5,6.1-7.5,5.5-11.8,5.8c-9.9,0.6-19.8,0.3-29.7,0.1c-3.3-0.1-5.7-1.5-6.6-5.4c-1.2-5.1-2.3-9.9-1.4-15.4\n    c0.9-5.4-0.6-11.3-0.5-16.9c0.1-4.3,1-8.7,2-13c1.5-6.5,6-10.5,12.1-12.8c1-0.4,1.7-1.4,2.5-2.1C1907.7,0,1924.3,0,1941,0z"}),O("path",{d:"M797,0c5,1.9,9.9,4.1,12.5,9.1c1.7,3.3,3.8,6.8,4,10.3c0.6,12.7,0.4,25.4,0.5,38c0,3-5.9,8.1-9.8,8.2\n    c-10.6,0.1-21.2,0.1-31.8,0c-4.2,0-8.7-5.2-9-10.2c-0.3-6.9-0.1-13.8-0.6-20.7c-0.5-8.4-4.5-14.9-12-19C742,11,741.1,8.4,746,0\n    C763,0,780,0,797,0z"}),O("path",{d:"M725,0c6.8,6.5,6.3,10.3-2.3,14.7c-6,3.1-10.8,7.4-12.6,13.9c-1.2,4.5-0.9,9.5-1.1,14.3c-0.2,5.4-0.1,10.8-0.5,16.1\n    c-0.2,3.3-5.7,6.7-10.5,6.7c-8.8-0.1-17.6-0.6-26.3-0.4c-9.5,0.2-13-2.5-13.3-12.3c-0.2-7.8,0.1-15.7,0.3-23.5\n    c0.1-4.9-0.4-10,1.1-14.5c2.3-7.1,7-12.5,15.3-13.2c0.4,0,0.6-1.1,0.9-1.7C692.3,0,708.7,0,725,0z"}),O("path",{d:"M2013,0c1.2,1,2.3,2.6,3.6,3c10.4,2.6,12.4,11,12.9,19.6c0.6,10.5,0.3,21.1,0.1,31.7c-0.1,4.5-1.8,9.2-6.4,10.8\n    c-3.2,1.2-7,0.6-10.6,0.6c-7.7,0.1-15.3,0.1-23,0c-6.9-0.1-10.6-5.8-10.5-12.6c0.1-9-0.2-18.3-2.4-27c-1-4.2-6.2-8-10.4-10.5\n    c-8-4.8-9.3-8.9-3.3-15.5C1979.7,0,1996.3,0,2013,0z"}),O("path",{d:"M3824,0c4.8,7.2,4.1,11.2-3.9,15.7c-10.3,5.8-12.9,15-12.8,25.7c0,5.4,0.2,10.9,0.1,16.3c-0.1,3.2-5.4,7.9-9.2,7.9\n    c-11.1-0.1-22.1-0.4-33.2-0.7c-2.9-0.1-8.3-6.5-8.2-9.4c0.2-10.4,0.8-20.9,0.4-31.3c-0.4-9.9,6.8-21.1,18-23.1\n    c0.3-0.1,0.6-0.7,0.9-1.1C3792,0,3808,0,3824,0z"}),O("path",{d:"M2608,132c-0.8-0.4-1.6-1.3-2.4-1.2c-5,0.1-5.9-3.1-6.3-7.1c-0.4-4.6,2.8-5.6,6.1-6.1c5.5-0.9,11-1.4,16.5-2.1\n    c11.6-1.6,17-9.2,17.4-20.4c0.1-3.9,0.5-7.9,1.2-11.8c0.7-3.9,6.8-6.9,13-6.8c7.7,0.1,15.5,0.1,23.2,0.6\n    c10.8,0.7,14.5,5.1,13.9,13.6c-0.6,8.6-0.3,17.4-2.5,25.6c-1.2,4.5-6.4,8.5-10.6,11.5c-3,2.1-7.4,2.2-11.1,3.3\n    c-0.5,0.2-0.9,0.8-1.4,1.2C2646,132,2627,132,2608,132z"}),O("path",{d:"M2578,0c3.8,3.3,9.2,5.5,8.7,12c-0.2,2.7,0,5.5,0,8.2c-0.1,11.1,0,22.3-0.5,33.4c-0.4,8.7-5.3,11.8-13.1,12\n    c-7,0.2-14.1-0.1-21.1,0.1c-12.7,0.4-17.6-4.9-16.5-17.5c0.5-5,0.1-10.1,0.1-15.1c0-6.7-0.1-13.5,0.1-20.2c0.2-5.5,2.5-10,7.4-12.8\n    C2554.7,0,2566.3,0,2578,0z"}),O("path",{d:"M3215,0c6.5,4,6.9,10.3,6.7,17c-0.3,12.1,0,24.3-0.1,36.5c0,8.9-3.3,11.7-12.2,11.5c-8.6-0.1-17.1,0.5-25.7,0.6\n    c-8,0.1-11-2.8-11.4-11.4c-0.4-10.8-0.7-21.7-0.9-32.5c-0.1-4,0.1-8,0.3-12.1c0.3-4.9,2.5-8.1,7.9-8.4c0.8,0,1.6-0.8,2.4-1.3\n    C3193,0,3204,0,3215,0z"}),O("path",{d:"M1889,132c-3.1-2.7-6.9-5-9.1-8.3c-2.7-4.3-5-9.2-5.8-14.2c-1.2-7.2,0.8-14.6-0.6-22c-1.1-5.8,8.1-11.5,15-10.9\n    c6.4,0.6,13,0.4,19.4-0.2c5.9-0.6,13.6,2.1,16.5,7.3c1,1.9,0.6,4.6,0.9,6.9c0.8,6.3-0.8,13.4,4.2,18.6c2.6,2.7,6,4.8,8.9,7.2\n    c2.1,1.7,4.7,3.1,6.1,5.2c3.1,4.7-1.2,7.3-3.6,10.4C1923.7,132,1906.3,132,1889,132z"}),O("path",{d:"M672,132c-0.4-0.9-0.6-2.5-1.2-2.6c-8.4-1.4-9.8-8.7-11.2-14.8c-1.5-6.3-1.1-13-1.1-19.6c0-4.5-1-9.5,2.8-13.3\n    c1.6-1.6,3.4-4,5.4-4.2c4.6-0.6,9.3-0.1,14-0.2c4.8-0.1,9.6-0.8,14.4-0.4c3.7,0.3,7.5,1.2,10.7,2.8c1.5,0.8,2.4,3.7,2.8,5.8\n    c1.2,6.7,1.8,13.4,3.2,20.1c1.2,5.4,5.6,8.1,10.3,10.5c3.4,1.7,7.2,3.3,7.2,8.4c0,4.5-3.5,5.6-6.3,7.5C706,132,689,132,672,132z"}),O("path",{d:"M1963,132c-2.9-2.9-6.3-5.9-3.1-10.2c1.9-2.7,4.5-5.6,7.5-6.8c7.9-3.1,10.7-8.9,11-16.8c0.2-5,0.9-10.1,1.3-15.1\n    c0.4-4.2,4.3-5,6.9-5.2c9.6-0.6,19.3-0.5,29-0.3c3.2,0.1,7,0.4,9.5,2.1c2.2,1.5,4.2,5,4.3,7.6c0.2,9.1-0.2,18.2-0.9,27.3\n    c-0.6,7.7-7.4,13.8-15.5,15.2c-1.1,0.2-2,1.4-3,2.1C1994.3,132,1978.7,132,1963,132z"}),O("path",{d:"M746,132c-3.6-3.7-3.8-8.3-1.3-12c1.7-2.6,5.4-4,8.4-5.4c6.3-3,8.7-8.1,9.3-14.8c0.5-5.8,1.8-11.5,2.7-17.2\n    c1-5.8,6.6-5,9.8-5.4c8.6-1.1,17.4-0.8,26-0.7c6,0.1,13.5,4.7,12.8,12.4c-0.8,8.3-0.2,16.6-0.4,25c-0.1,5.8-8.8,15.6-14.6,17\n    c-0.7,0.2-1.2,0.8-1.8,1.2C780,132,763,132,746,132z"}),O("path",{d:"M3778,132c-2.3-0.6-4.6-1.1-6.8-1.9c-7.1-2.6-15-12.9-14.1-21.8c0.6-5.1-0.5-10.4-0.4-15.5c0.1-3.6,0.7-7.4,1.7-10.9\n    c0.4-1.3,2.3-2.6,3.8-3.1c11-3.4,22.3-2.9,33.6-1.4c1.4,0.2,2.9,0.5,4.3,0.7c4.8,0.5,7.2,3.5,7.2,8.2c0,8.3,0.4,16.5,4.3,24.1\n    c0.4,0.8,1.4,1.5,2.3,2c3.5,2.2,7.2,4,10.6,6.5c2.1,1.5,3.9,3.6,1.8,6.8c-1.2,1.9-1.7,4.2-2.5,6.3C3808.7,132,3793.3,132,3778,132z"}),O("path",{d:"M1336,0c7.9,6,8.2,14.3,7.6,23.3c-0.7,10.6-0.2,21.3-0.3,31.9c-0.1,5.8-3.2,9.2-9,9.5c-8.6,0.4-17.2,0.8-25.8,1\n    c-6.9,0.1-10.4-3.5-10.6-10.4c-0.4-14.4-0.6-28.7-1.1-43.1c-0.1-4.3,0.7-7.6,5.1-9.4c1.2-0.5,2.1-1.8,3.2-2.8\n    C1315.3,0,1325.7,0,1336,0z"}),O("path",{d:"M2542,132c-0.5-1.1-0.8-2.8-1.7-3.1c-3.6-1.5-4.1-4.4-4.2-7.7c-0.3-9.4-0.7-18.7-0.7-28.1c0-3.3,1.1-6.8,2.3-10\n    c2.6-6.9,9.2-5.9,14.7-6.4c4.1-0.4,8.4,0.2,12.5,0.5c4.9,0.3,9.8,0.5,14.6,1.1c5.3,0.6,6.5,5.5,6.7,9.2c0.4,11.6,0.6,23.3-0.5,34.8\n    c-0.3,3.5-5.1,6.5-7.8,9.7C2566,132,2554,132,2542,132z"}),O("path",{d:"M110,132c-0.2-0.6-0.2-1.5-0.6-1.8c-8.3-5.1-7-13.5-6.9-21.2c0.1-7,1-14.1,1.3-21.1c0.2-5.6,4.2-10.8,9.6-10.9\n    c9.7-0.2,19.4,0.1,29.1,0.3c5.4,0.1,8.8,2.5,9.3,8.2c0.4,4.9,0.9,9.8,0.9,14.7c0.1,7.4,0.7,14.9-0.3,22.2c-0.5,3.5-4.1,6.5-6.3,9.7\n    C134,132,122,132,110,132z"}),O("path",{d:"M3182,132c-0.9-0.4-1.9-1.1-2.9-1.3c-3.6-0.9-4.6-2.5-5.8-6.7c-3-10.6,0.5-21-1.7-31.5c-1.9-9.2,4.3-15.1,14-15.3\n    c9.1-0.2,18.1,0,27.2,0.2c4.5,0.1,8.7,4.8,8.8,9.8c0.1,11,0,22.1,0.1,33.1c0.1,5.7-3.3,9-7.7,11.7C3203.3,132,3192.7,132,3182,132z"}),O("path",{d:"M0,81c10.9-5.9,22.8-3.2,34.3-4.1c6.6-0.5,10.9,1.8,14.1,7c0.7,1.2,1.1,2.8,1.1,4.2c0.1,7,0.1,14,0.1,21\n    c0,3.9,0.7,8-0.2,11.7c-1.2,4.6-2.9,9.6-8.4,11.2c-13.7,0-27.3,0-41,0C0,115,0,98,0,81z"}),O("path",{d:"M1304,132c-7.6-4.2-7.1-11.9-7.5-18.8c-0.5-7-0.3-14,0-21c0.4-10.6,4.5-15.3,15.6-15.3c7.4,0,14.8-0.2,22.1,0.6\n    c6.1,0.7,8.8,4.6,8.9,10.9c0.2,9.1,0.2,18.1,0.6,27.2c0.3,7.3-0.7,13.7-8.7,16.5C1324.7,132,1314.3,132,1304,132z"}),O("path",{d:"M3846,132c-0.2-1-0.1-2.5-0.7-3.1c-4.8-4.6-4.3-8.3,1.8-11.7c5.2-2.8,10.4-5.5,12.1-11.9c0.7-2.8,1.6-5.5,2.3-8.3\n    c1.4-6.2,6.9-6.5,11.4-7c6.9-0.8,13.9-0.2,20.9-0.2c1.8,0,3.6-0.1,5.4,0.2c3.9,0.7,8.9-1.4,10.7,4.4c0.1,0.4,1.4,0.3,2.2,0.5\n    c0,7,0,14,0,21c-0.3,0.1-0.8,0.2-0.8,0.4c-2.7,9.2-8.8,13.9-18.3,14.4c-0.6,0-1.2,0.8-1.9,1.2C3876,132,3861,132,3846,132z"}),O("path",{d:"M3912,30c-8.8,7.5-19.6,4.6-29.7,5.5c-3.5,0.3-7.2-0.8-10.8-1c-5.8-0.3-9.6-3.4-11.8-8.3c-2.4-5.3-6.3-8.5-11.2-11\n    c-2-1-3.7-2.6-5.3-4.3c-3.2-3.2-0.5-5.8,1.3-8.4c0.6-0.8,1-1.7,1.5-2.6c15.7,0,31.3,0,47,0c1.1,0.7,2.2,1.9,3.4,2.1\n    c7.7,1,11.4,4.3,13.6,12.2c0.1,0.4,1.3,0.5,2,0.7C3912,20,3912,25,3912,30z"}),O("path",{d:"M1365,132c-0.7-0.6-1.5-1.8-2.1-1.7c-6.2,1-7.7-4-7.9-7.8c-0.2-3.7,1.8-7.8,3.8-11.2c0.9-1.4,4.3-1.8,6.6-1.8\n    c8.6,0.1,17.3,0.4,25.9,0.8c5.2,0.2,9.8,5,9.9,9.8c0,5.9-3.4,9.7-9.3,10.6c-1,0.2-1.9,0.8-2.9,1.3C1381,132,1373,132,1365,132z"}),O("path",{d:"M3241,132c-4.3-2.3-7.4-5.3-7.9-10.7c-0.5-5.3,2.6-10.8,7.8-11.1c8.5-0.4,17-0.6,25.5-0.3c6.8,0.3,12.5,8,10,13.9\n    c-1.2,3-3.3,6.9-8.2,6.4c-1.3-0.1-2.7,1.1-4.1,1.7C3256.3,132,3248.7,132,3241,132z"}),O("path",{d:"M3268,0c0.7,0.5,1.4,1.1,2.2,1.5c3.2,1.6,6,3.4,5.9,7.6c-0.1,3.3-0.6,5.7-3,8.8c-5.1,6.5-11.3,3.5-17.1,4.1\n    c-3,0.3-6.2-0.6-9.2-0.1c-6.5,1.1-10.8-0.9-13.5-6.9c-1.9-4.3,0-9.9,4.5-13.2c0.7-0.5,1.4-1.2,2.1-1.7C3249.3,0,3258.7,0,3268,0z"}),O("path",{class:"st0",d:"M3891,132c0.6-0.4,1.2-1.2,1.9-1.2c9.5-0.5,15.7-5.2,18.3-14.4c0.1-0.2,0.5-0.3,0.8-0.4c0,5.3,0,10.7,0,16\n    C3905,132,3898,132,3891,132z"}),O("path",{class:"st0",d:"M3912,15c-0.7-0.2-1.8-0.3-2-0.7c-2.2-7.9-5.9-11.2-13.6-12.2c-1.2-0.2-2.3-1.4-3.4-2.1c6.2,0,12.5,0,19,0\n    C3912,5,3912,10,3912,15z"}),O("path",{d:"M3254.9,65.7c-3.3,0-6.7,0.5-9.9-0.1c-3.5-0.7-7.2-1.8-9.9-4c-4.5-3.5-2-14.3,3.7-15.5c10.2-2.2,20.5-2.9,30.8-0.3\n    c5,1.3,7.1,5.5,6.6,9.6c-0.4,3.3-0.6,8.2-6.4,8.6c-5,0.3-9.8,1.6-14.8,2.5C3255,66.2,3255,65.9,3254.9,65.7z"})],-1),tt={key:0,class:"link-btn",href:"https://timetable.ffm.to/someway",target:"_blank"},it={key:0,class:"tracks"},st=I("Some way "),ot=O("div",{class:"spinner"},null,-1),nt=I("Dream "),at=O("div",{class:"spinner"},null,-1);Ge.render=function(e,t,i,s,o,n){const a=Y("Menu"),r=Y("GL"),c=Y("Nav"),h=Y("Controls"),l=Y("AudioAnalyzer");return S(),W(L,null,[O("div",Je,[O(P,{name:"fade2"},{default:X((()=>[!o.menuOpen&&o.uiVisible?(S(),W("div",Qe,[Ze,et])):H("",!0)])),_:1})]),O(P,{name:"fade2"},{default:X((()=>[!o.menuOpen&&o.ready&&o.uiVisible?(S(),W("a",tt,"Buy/Stream")):H("",!0)])),_:1}),O(P,{name:"fade2"},{default:X((()=>[!o.menuOpen&&o.ready&&o.uiVisible?(S(),W("span",{key:0,class:"menu-btn",onClick:t[1]||(t[1]=R((e=>o.menuOpen?o.menuOpen=!1:o.menuOpen=!0),["stop","prevent"]))},F(o.menuOpen?"X":"+"),1)):H("",!0)])),_:1}),O(P,{name:"menu"},{default:X((()=>[o.menuOpen?(S(),W(a,{key:0,onClose:t[2]||(t[2]=e=>o.menuOpen=!1)})):H("",!0)])),_:1}),O(P,{name:"fade"},{default:X((()=>[o.ready&&!o.menuOpen&&o.uiVisible?(S(),W("div",it,[O("span",{class:{active:0===e.$store.state.track},onClick:t[3]||(t[3]=R((t=>e.$store.commit("setTrack",0)),["prevent"]))},[st,ot],2),O("span",{class:{active:1===e.$store.state.track},onClick:t[4]||(t[4]=R((t=>e.$store.commit("setTrack",1)),["prevent"]))},[nt,at],2)])):H("",!0)])),_:1}),O(r,{scroll:o.scrollY,onReady:t[5]||(t[5]=e=>o.ready=!0),onClick:t[6]||(t[6]=e=>o.menuOpen=!1)},null,8,["scroll"]),O(c),O(h),O(l,{src:0===e.$store.state.track?"/Some_way.mp3":"/Dream.mp3"},null,8,["src"])],64)};const rt=c({state:()=>({track:0,muted:!1}),mutations:{setTrack(e,t){e.track=t,console.log("set",t)},setMute(e,t){e.muted=t}}}),ct=N(Ge);ct.use(rt),ct.mount("#app");
