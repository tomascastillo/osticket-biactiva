var buttons = document.getElementsByTagName('button');

Array.prototype.forEach.call(buttons, function (b) {
    b.addEventListener('click', createRipple);
});


function createRipple (e) {
		// alert('x');
    var circle = document.createElement('div');
    this.appendChild(circle);

    var d = Math.max(this.clientWidth, this.clientHeight);

    circle.style.width = circle.style.height = d + 'px';

var rect = this.getBoundingClientRect();
circle.style.left = e.clientX - rect.left -d/2 + 'px';
circle.style.top = e.clientY - rect.top - d/2 + 'px';
circle.classList.add('ripple');
		
	setTimeout(function(){
		circle.remove();
	},500);
}