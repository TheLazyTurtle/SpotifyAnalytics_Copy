const vGalleryControls = ['up', 'down'];
const vGalleryContainer = document.querySelector('.v-gallery-container');
const vGalleryControlContainer = document.querySelector('.v-gallery-controls');
const vGalleryItems = document.querySelectorAll('.v-gallery-item');

class VerticalCarousel {
    contructor(container, items, controls) {
	this.carouselContainer = container;
	this.carouselControls = controls;
	this.carouselArray = [...items];
    }

    // Assign initial css classes for gallery and nav
    setInitalState() {
	this.carouselArray[0].classList.add('v-gallery-item-selected');
	this.carouselArray[1].classList.add('v-gallery-item-bottom');
    }

    // Update the order state of the carousel with css classes
    setCurrentState(target, selected, bottom) {
	selected.forEach(el => {
	    el.classList.remove('v-gallery-item-selected');

	    if (target.className == "v-gallery-controls-previous") {
		el.classList.add('v-gallery-item-bottom');
	    } else {
		el.classList.add("v-gallery-item-bottom");
	    }
	});

	bottom.forEach(el => {
	    el.classList.remove('v-gallery-item-bottom');

	    if (target.className == "v-gallery-controls-previous") {
		el.classList.add('v-gallery-item-selected');
	    } else {
		el.classList.add("v-gallery-item-bottom");
	    }
	});
    }

    setControls() {
	this.carouselControls.forEach(control => {
	    galleryControlsContainer.appendChild(document.createElement('button')).className = `v-gallery-controls-${control}`;
	}); 
 
	!!galleryControlsContainer.childNodes[0] ? galleryControlsContainer.childNodes[0].innerHTML = this.carouselControls[0] : null;
	!!galleryControlsContainer.childNodes[1] ? galleryControlsContainer.childNodes[1].innerHTML = this.carouselControls[1] : null;
   }

    // click listener to trigger setCurrentState
    useControls() {
	const triggers = [...galleryControlsContainer.childNodes];
	triggers.forEach(control => {
	    control.addEventListener('click', () => {
		const target = control;
		const selectedItem = document.querySelectorAll('.v-gallery-item-selected');
		const bottomItem = document.querySelectorAll('.v-gallery-item-bottom');

		this.setCurrentState(target, selectedItem, bottomItem);
	    });
	});
    }
}

const vCarousel = new VerticalCarousel(vGalleryContainer, vGalleryItems, vGalleryControls);
vCarousel.setControls();
vCarousel.setInitalState();
vCarousel.useControls();
