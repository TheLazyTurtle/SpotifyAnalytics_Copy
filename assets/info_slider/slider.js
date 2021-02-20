const galleryControls = ['previous', 'next'];
const galleryContainer = document.querySelector('.gallery-container');
const galleryControlsContainer = document.querySelector('.gallery-controls');
const galleryItems = document.querySelectorAll('.gallery-item');

class Carousel {
  constructor(container, items, controls) {
    this.carouselContainer = container;
    this.carouselControls = controls;
    this.carouselArray = [...items];
  }

  // Assign initial css classes for gallery and nav items
  setInitialState() {
    this.carouselArray[0].classList.add('gallery-item-next-left');
    this.carouselArray[1].classList.add('gallery-item-left');
    this.carouselArray[2].classList.add('gallery-item-selected');
    this.carouselArray[3].classList.add('gallery-item-right');
    this.carouselArray[4].classList.add('gallery-item-next-right');

  }

  // Update the order state of the carousel with css classes
  setCurrentState(target, selected, left, right, nextLeft, nextRight) {

    selected.forEach(el => {
      el.classList.remove('gallery-item-selected');

      if (target.className == 'gallery-controls-previous btn') {
        el.classList.add('gallery-item-right');
      } else {
        el.classList.add('gallery-item-left');
      }
    });

    left.forEach(el => {
      el.classList.remove('gallery-item-left');

      if (target.className == 'gallery-controls-previous btn') {
        el.classList.add('gallery-item-selected');
      } else {
        el.classList.add('gallery-item-next-left');
      }
    });

    right.forEach(el => {
      el.classList.remove('gallery-item-right');

      if (target.className == 'gallery-controls-previous btn') {
        el.classList.add('gallery-item-next-right');
      } else {
        el.classList.add('gallery-item-selected');
      }
    });

    nextLeft.forEach(el => {
      el.classList.remove('gallery-item-next-left');

      if (target.className == 'gallery-controls-previous btn') {
        el.classList.add('gallery-item-left');
      } else {
        el.classList.add('gallery-item-next-right');
      }
    });

    nextRight.forEach(el => {
      el.classList.remove('gallery-item-next-right');

      if (target.className == 'gallery-controls-previous btn') {
        el.classList.add('gallery-item-next-left');
      } else {
        el.classList.add('gallery-item-right');
      }
    });
}

// Construct the carousel controls
  setControls() {
    this.carouselControls.forEach(control => {
      galleryControlsContainer.appendChild(document.createElement('button')).className = `gallery-controls-${control} btn`;
    }); 

    !!galleryControlsContainer.childNodes[0] ? galleryControlsContainer.childNodes[0].innerHTML = this.carouselControls[0] : null;
    !!galleryControlsContainer.childNodes[1] ? galleryControlsContainer.childNodes[1].innerHTML = this.carouselControls[1] : null;
  }
 
  // Add a click event listener to trigger setCurrentState method to rearrange carousel
  useControls() {
    const triggers = [...galleryControlsContainer.childNodes];

    triggers.forEach(control => {
      control.addEventListener('click', () => {
        const target = control;
        const selectedItem = document.querySelectorAll('.gallery-item-selected');
        const leftSelectedItem = document.querySelectorAll('.gallery-item-left');
        const rightSelectedItem = document.querySelectorAll('.gallery-item-right');
        const nextLeftCarouselItem = document.querySelectorAll('.gallery-item-next-left');
        const nextRightCarouselItem = document.querySelectorAll('.gallery-item-next-right');

        this.setCurrentState(target, selectedItem, leftSelectedItem, rightSelectedItem, nextLeftCarouselItem, nextRightCarouselItem);
      });
    });
  }
}

const exampleCarousel = new Carousel(galleryContainer, galleryItems, galleryControls);
exampleCarousel.setControls();
exampleCarousel.setInitialState();
exampleCarousel.useControls();
