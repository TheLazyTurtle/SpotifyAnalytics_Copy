import { useContext } from "react";
import { PlayedAPI } from "../api/PlayedAPI";
import { LoggedInUserContext } from "../App";
import InputField from "../inputField/InputField";
import { inputField } from "../inputField/InputFieldWrapper";

function Header() {
    const loggedInUser = useContext(LoggedInUserContext);

    const inputField: inputField = {
        name: "search",
        type: "text",
        placeholder: "Search",
        startValue: "",
        autocompleteFunction: PlayedAPI.search
    }

    function toggleNotificationScreen() {

    }

    function handleLogout() {
        const cookies = document.cookie.split(";");

        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i];
            const eqPos = cookie.indexOf("=");
            const name = eqPos > -1 ? cookie.substring(0, eqPos) : cookie;
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
        }

        window.location.href = "/login";
    }

    return (
        <>
            {!loggedInUser.guest &&
                <div className="bg-dark w-100 py-2 px-2 d-md-block d-none">
                    <div className="row mx-auto w-75">
                        <div className="col-4 d-inline-block">
                            <a href="/" className="text-decoration-none">
                                <h3>Spotify Analytics</h3>
                            </a>
                        </div>
                        <div className="col-4 d-inline-block">
                            <InputField onChange={() => { }} inputField={inputField} />
                        </div>
                        <div className="col-4 d-inline-block text-center text-custom-green">
                            <i onClick={toggleNotificationScreen} className="fas fa-envelope px-2"></i>
                            <i onClick={() => { window.location.href = "/profile" }} className="fas fa-user-alt px-2"></i>
                            <i onClick={handleLogout} className="fas fa-arrow-right px-2"></i>
                        </div>
                    </div>
                </div>
            }
        </>
    );
}

export default Header;

    // return (
    //     <>
    //         <header className="App-header mb-md-5">
    //             <Navbar fixed="top" bg="dark" variant="dark" expand="md" className="mb-3 d-none d-md-block">
    //                 <Container fluid>
    //                     <Navbar.Brand href="/">Spotify Analytics</Navbar.Brand>
    //                     <Navbar.Offcanvas id={"offcanvasNavbar-expand-md"} aria-labelledby={"offcanvasNavbarLabel-expand-md"} placement="end">
    //                         <Offcanvas.Header closeButton>
    //                             <Offcanvas.Title id={"offcanvasNavbarLabel-expand-md"}> Offcanvas </Offcanvas.Title>
    //                         </Offcanvas.Header>
    //                         <Form className="d-flex">
    //                             <InputField onChange={handleOnSearchChange} inputField={inputField} />
    //                         </Form>
    //                         <Offcanvas.Body>
    //                             <Nav className="justify-content-end flex-grow-1 pe-3">
    //                                 <Nav.Link href="/">Home</Nav.Link>
    //                                 <Nav.Link href="/Link">Link</Nav.Link>
    //                                 <Nav.Link href="../../api/system/login.php">API</Nav.Link>
    //                                 <NavDropdown title="Dropdown" id={"offcanvasNavbarDropdown-expand-md"} >
    //                                     <NavDropdown.Item href="#action3">Action</NavDropdown.Item>
    //                                     <NavDropdown.Item href="#action4"> Another action </NavDropdown.Item>
    //                                     <NavDropdown.Divider />
    //                                     <NavDropdown.Item href="#action5"> Something else here </NavDropdown.Item>
    //                                 </NavDropdown>
    //                             </Nav>
    //                         </Offcanvas.Body>
    //                     </Navbar.Offcanvas>
    //                 </Container>
    //             </Navbar>
    //         </header>
    //     </>
    // );
