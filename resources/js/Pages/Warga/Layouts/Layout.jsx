import React, { useState } from "react"
import '../../../../css//warga/layout.css'
import Sidebar from './Sidebar'
import Footer from './Footer'
import Topbar from './Topbar'
// import { ModalSidebar } from './Modal'

export default function layout({ children }) {
    return (
        <>
            <div id="wrapper">
                <ul className="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block"
                    id="accordionSidebar">
                    <Sidebar />
                </ul>
                {/* <ModalSidebar /> */}
                <div id="content-wrapper" className="main-content d-flex flex-column">
                    {children}
                    <Footer />
                </div>
            </div>
        </>
    )
}