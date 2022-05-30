import { Container, Form, FormControl, Nav, Navbar, NavDropdown, Offcanvas } from "react-bootstrap";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import HomePage from "../home/HomePage";
import AlbumsPage from "../album/AlbumsPage";
import LoginPage from "../login/LoginPage";
import NotFound from "../notFound/NotFound";
import { useState } from "react";
import React from "react";
import { User } from "../user/User";
import ArtistPage from "../artist/ArtistPage";

interface IUserContext {
    user: User;
    changeUser?: (user: User) => void;
};

const startState: User = {
    userID: "",
    username: "",
    isAdmin: false,
    img: "",
    jwt: ""
};

export const UserContext = React.createContext<IUserContext>({ user: startState });

function Header() {
    const [user, setUser] = useState<User>(startState);

    const changeUser = (newUser: User) => {
        setUser(newUser);
        console.log(newUser)
        // validateLogin(newUser.jwt);
    }

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
        // TODO: Check that the userContext does not rerender the entirety of the code below when updated
        <>
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
                <UserContext.Provider value={{ user, changeUser }}>
                    <Routes>
                        <Route path="/" element={<HomePage />} />
                        <Route path="/login" element={<LoginPage />} />
                        <Route path="/album/:artistID" element={<AlbumsPage />} />
                        <Route path="/artist/:artistID" element={<ArtistPage />} />
                        <Route path="*" element={<NotFound />} />
                        <Route path="/api" element="../../api/system/login.php" />
                    </Routes>
                </UserContext.Provider>
            </Router>
        </>
    );
}

export default Header;

