import { Controller } from "@hotwired/stimulus";
import ReactDOM from "react-dom";
import React from "react";
import GalleryDropzone from "../components/galleryDropzone";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    "galleryCategoryId": Number
  };

  connect() {
    ReactDOM.render(
      <GalleryDropzone galleryCategoryId={this.galleryCategoryIdValue} />,
      this.element
    )
  }
}
