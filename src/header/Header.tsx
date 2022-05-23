import { Container, Form, FormControl, Nav, Navbar, NavDropdown, Offcanvas } from "react-bootstrap";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import HomePage from "../home/HomePage";
import AlbumsPage from "../album/AlbumsPage";
import LoginPage from "../login/LoginPage";
import NotFound from "../notFound/NotFound";

interface HeaderProps {
    loggedIn: boolean;
    userProfileImg?: string;
}

// NOTE: We could probably do something with useContext() to check if the user is logged in
// TODO: FIX that everything is in the header
function Header(props: HeaderProps) {
    let desktopSize = "md";
    return (
        <Router>
            <header className="App-header mb-md-5">
                <Navbar fixed="top" bg="dark" variant="dark" expand={desktopSize} className="mb-3 d-none d-md-block">
                    <Container fluid>
                        <Navbar.Brand href="/">Spotify Analytics</Navbar.Brand>
                        {/* <Navbar.Toggle aria-controls={`offcanvasNavbar-expand-${desktopSize}`} /> */}
                        <Navbar.Offcanvas id={`offcanvasNavbar-expand-${desktopSize}`} aria-labelledby={`offcanvasNavbarLabel-expand-${desktopSize}`} placement="end">
                            <Offcanvas.Header closeButton>
                                <Offcanvas.Title id={`offcanvasNavbarLabel-expand-${desktopSize}`}> Offcanvas </Offcanvas.Title>
                            </Offcanvas.Header>
                            <Offcanvas.Body>
                                <Nav className="justify-content-end flex-grow-1 pe-3">
                                    <Nav.Link href="/">Home</Nav.Link>
                                    <Nav.Link href="/Link">Link</Nav.Link>
                                    <NavDropdown title="Dropdown" id={`offcanvasNavbarDropdown-expand-${desktopSize}`} >
                                        <NavDropdown.Item href="#action3">Action</NavDropdown.Item>
                                        <NavDropdown.Item href="#action4"> Another action </NavDropdown.Item>
                                        <NavDropdown.Divider />
                                        <NavDropdown.Item href="#action5"> Something else here </NavDropdown.Item>
                                    </NavDropdown>
                                </Nav>
                                <Form className="d-flex">
                                    <FormControl type="search" placeholder="Search" className="me-2" aria-label="Search" />
                                </Form>
                            </Offcanvas.Body>
                        </Navbar.Offcanvas>
                    </Container>
                </Navbar>
            </header>
            <Routes>
                <Route path="/" element={<HomePage />} />
                <Route path="/login" element={<LoginPage />} />
                <Route path="/album/:artistID" element={<AlbumsPage />} />
                <Route path="*" element={<NotFound />} />
            </Routes>
        </Router>
    );
}

export default Header;
