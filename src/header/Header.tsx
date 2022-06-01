import { Container, Form, FormControl, Nav, Navbar, NavDropdown, Offcanvas } from "react-bootstrap";

function Header() {
    // async function validateLogin(jwt: string) {
    //     const loggedIn = await SystemAPI.validateToken(jwt);
    //     if (loggedIn instanceof Error || loggedIn.message == "Access denied") {
    //         if (!window.location.href.includes("/login")) {
    //             console.log(user)
    //             // window.location.href = "/login";
    //         }
    //         return;
    //     }
    // }
    //
    // useEffect(() => {
    //     validateLogin(user.jwt);
    // }, [])

    let desktopSize = "md";
    return (
        <>
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
                                    <Nav.Link href="../../api/system/login.php">API</Nav.Link>
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
        </>
    );
}

export default Header;

