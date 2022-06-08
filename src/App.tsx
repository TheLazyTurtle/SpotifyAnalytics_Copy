import './App.css';
import Header from './header/Header';
import 'bootstrap/dist/css/bootstrap.css';
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import HomePage from "./home/HomePage";
import AlbumsPage from "./album/AlbumsPage";
import LoginPage from "./login/LoginPage";
import NotFound from "./notFound/NotFound";
import ArtistPage from "./artist/ArtistPage";
import MobileHeader from './header/MobileHeader';
import ProfilePage from './user/ProfilePage';
import { useState, createContext, useEffect } from 'react';
import { User } from './user/User';
import { SystemAPI } from './api/SystemAPI';

export const LoggedInUserContext = createContext<User>({ guest: true } as User);

function App() {
    const [loggedInUser, setLoggedInUser] = useState<User>({ guest: true } as User);
    const [loading, setLoading] = useState(true);

    async function validateLogin() {
        setLoading(true);
        const validationResult = await SystemAPI.validateToken();

        if (validationResult.success) {
            setLoggedInUser(validationResult.data)
            setLoading(false);
        } else {
            setLoading(false);

            if (window.location.pathname === "/login") {
                return;
            }

            return window.location.href = makeRedirectUrl();
        }
    }

    function makeRedirectUrl() {
        const currentPage = window.location.pathname.replace("/", "")
        return `/login?redirect=${currentPage}`
    }

    useEffect(() => {
        validateLogin();
    }, []);

    // TODO: Validate login here as every page is technically this page
    return (
        <div className="App">
            <Header />
            {!loading &&
                <LoggedInUserContext.Provider value={loggedInUser}>
                    <Router>
                        <Routes>
                            <Route path="/" element={<HomePage />} />
                            <Route path="/login" element={<LoginPage />} />
                            <Route path="/album/:artistID" element={<AlbumsPage />} />
                            <Route path="/artist/:artistID" element={<ArtistPage />} />
                            <Route path="/profile" element={<ProfilePage />} />
                            <Route path="/:username" element={<ProfilePage />} />
                            <Route path="*" element={<NotFound />} />
                        </Routes>
                    </Router>
                </LoggedInUserContext.Provider>
            }
            <MobileHeader />
        </div >
    );
}

export default App;
