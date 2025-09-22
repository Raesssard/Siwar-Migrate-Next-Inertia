import React from "react";

export default function Footer() {
  return (
    <footer className="sticky-footer">
      <div className="container my-auto">
        <div className="copyright text-center my-auto">
          <span>&copy; WargaKita {new Date().getFullYear()}</span>
        </div>
      </div>
    </footer>
  );
}
