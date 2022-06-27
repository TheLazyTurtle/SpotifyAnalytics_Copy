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
import NotificationsPage from './notificatons/NotificationsPage';
import RegisterPage from './register/RegisterPage';

export interface Response<T> {
    status: number;
    data: {
        [K in keyof T]: T[K];
    };
};

export function redirectToLogin(link: boolean = false) {
    const currentPage = window.location.pathname.replace("/", "");
    const url = `/login?redirect=${currentPage}`;
    if (link) {
        return url;
    }

    window.location.href = url;
}

function App() {
    return (
        <div className="App">
            <Header />
            <Router>
                <Routes>
                    <Route path="/" element={<HomePage />} />
                    <Route path="/login" element={<LoginPage />} />
                    <Route path="/register" element={<RegisterPage />} />
                    <Route path="/album/:artistID" element={<AlbumsPage />} />
                    <Route path="/artist/:artistID" element={<ArtistPage />} />
                    <Route path="/profile" element={<ProfilePage />} />
                    <Route path="/search" element={<SearchPage />} />
                    <Route path="/notifications" element={<NotificationsPage />} />
                    <Route path="/:username" element={<ProfilePage />} />
                    <Route path="*" element={<NotFound />} />
                </Routes>
            </Router>
            <MobileHeader />
        </div >
    );
}

export default App;
