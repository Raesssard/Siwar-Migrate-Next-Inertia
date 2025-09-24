import React, { useState } from "react"
import '../../css/layout.css'
import Sidebar from './Sidebar'
import Footer from './Footer'
import Topbar from "./Topbar"
// import { ModalSidebar } from './Modal'

export default function layout({ children }) {
    const [toggle, setToggle] = useState("")

    const handleToggle = (t) => {
        setToggle(t)
    }

    return (
        <>
            <div id="wrapper">
                <Sidebar toggleKeParent={handleToggle} />
                {/* <ModalSidebar /> */}
                <div id="content-wrapper" className={`main-content d-flex flex-column ${toggle}`}>
                    <div id="content">
                        <Topbar />
                        <div className="container-fluid">
                            <div className="row">
                                {children}
                            </div>
                        </div>
                    </div>
                    <Footer />
                </div>
            </div>
        </>
    )
}