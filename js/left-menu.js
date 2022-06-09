let akk_toggleMenu = document.querySelector('.toggleMenu');
let akk_nav = document.querySelector('.nav');
let akk_subnav = document.querySelectorAll('.nav > li > a');
akk_toggleMenu.onclick = (e) => {
	e.stopPropagation();
	e.preventDefault();
	e.target.classList.toggle('active');
	if (e.target.classList.contains('active'))
		akk_nav.style.display = 'block';
	else
		akk_nav.style.display = 'none';
};

akk_subnav.forEach((el) => {
	if (el.parentNode.classList.contains('akk_metka'))
		el.classList.add('parent');
	el.onclick = (e) => {
		e.preventDefault();
		e.stopPropagation();
		let parent = e.target.parentNode;
		let elems = document.querySelectorAll('.nav > li.akk_metka');
		
		if (!parent.classList.contains('akk_metka'))
			return;
		
		if (parent.classList.contains('hover')) {
			parent.classList.toggle('hover');
			return;
		}

		[].forEach.call(elems, (el) => {
			el.classList.remove('hover');
		});
		parent.classList.toggle('hover');
	};    
});
window.onclick = () => {
  akk_toggleMenu.classList.remove('active');
  akk_nav.style.display = 'none';
};