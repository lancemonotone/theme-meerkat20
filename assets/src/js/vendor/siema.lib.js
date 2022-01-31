"use strict";

import Siema from "./siema.min.js";

export default Object.assign(Siema.prototype, {
  addArrows() {
    this.prevArrow = document.createElement("button");
    this.nextArrow = document.createElement("button");
    this.prevArrow.tabindex = 0;
    this.nextArrow.tabindex = 0;
    this.prevArrow.innerHTML =
      '<span class="visuallyhidden">previous slide</span><svg class="icon icon--arrow prev"><use xlink:href="#icon-arrow" class="gobackarrow"></use></svg>';
    this.nextArrow.innerHTML =
      '<span class="visuallyhidden">next slide</span><svg class="icon icon--arrow next"><use xlink:href="#icon-arrow" class="gobackarrow"></use></svg>';

    let btn_row = document.createElement("div");
    let wrapper = document.createElement("div");
    btn_row.className = "siema-nav";
    let moduletype = this.selector.dataset.moduletype;
    wrapper.className = "siema-nav-wrapper " + moduletype;
    btn_row.appendChild(this.prevArrow);
    btn_row.appendChild(this.nextArrow);
    // this.selector.appendChild(wrapper);
    function insertBefore(el, referenceNode) {
      referenceNode.parentNode.insertBefore(el, referenceNode);
    }
    insertBefore(wrapper, this.selector);
    wrapper.appendChild(btn_row);

    // event handlers on buttons
    this.prevArrow.addEventListener("click", () => this.prev());
    this.nextArrow.addEventListener("click", () => this.next());
  },
});
