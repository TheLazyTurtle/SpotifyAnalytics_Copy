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
import SearchPage from './search/SearchPage';
import { useState, createContext, useEffect } from 'react';
import { User } from './user/User';
import NotificationsPage from './notificatons/NotificationsPage';

export const LoggedInUserContext = createContext<User>({ guest: true } as User);
export interface Response<T> {
    data: {
        [K in keyof T]: T[K]
    }
}

function App() {
    const [loggedInUser, setLoggedInUser] = useState<User>({ guest: true } as User);
    const [loading, setLoading] = useState(true);

    const redirectUrls = ["/", "/profile"]

    async function validateLogin() {
        setLoading(true);
        // const validationResult = await SystemAPI.validateToken();
        const validationResult = {
            success: true,
            data: {
                id: "1",
                username: "The Lazy Turtle",
                is_admin: true,
                img_url: "",
            } as User
        };

        if (validationResult.success) {
            setLoggedInUser(validationResult.data)
            setLoading(false);
        } else {
            setLoading(false);

            if (window.location.pathname === "/login") {
                return;
            }

            redirectUrls.map((url: string) => {
                if (url === window.location.pathname) {
                    // window.location.href = makeRedirectUrl();
                }
            })

            return;
        }
    }

    useEffect(() => {
        validateLogin();
    }, []);

    return (
        <div className="App">
            {!loading &&
                <LoggedInUserContext.Provider value={loggedInUser}>
                    <Header />
                    <Router>
                        <Routes>
                            <Route path="/" element={<HomePage />} />
                            <Route path="/login" element={<LoginPage />} />
                            <Route path="/album/:artistID" element={<AlbumsPage />} />
                            <Route path="/artist/:artistID" element={<ArtistPage />} />
                            <Route path="/profile" element={<ProfilePage />} />
                            <Route path="/search" element={<SearchPage />} />
                            <Route path="/notifications" element={<NotificationsPage />} />
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
