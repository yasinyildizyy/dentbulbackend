import { Controller } from "@hotwired/stimulus";
import ReactDOM from "react-dom";
import React from "react";
import CustomerCommentPhotoDropzone from "../components/customerCommentPhotoDropzone";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    "customerCommentId": Number
  };

  connect() {
    ReactDOM.render(
      <CustomerCommentPhotoDropzone customerCommentId={this.customerCommentIdValue} />,
      this.element
    )
  }
}
