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

function App() {
    // TODO: Validate login here as every page is technically this page
    return (
        <div className="App">
            <Header />
            <Router>
                <Routes>
                    <Route path="/" element={<HomePage />} />
                    <Route path="/login" element={<LoginPage />} />
                    <Route path="/album/:artistID" element={<AlbumsPage />} />
                    <Route path="/artist/:artistID" element={<ArtistPage />} />
                    <Route path="/profile" element={<ProfilePage />} />
                    <Route path="/:username" element={<ProfilePage />} />
                    <Route path="*" element={<NotFound />} />
                    <Route path="/api" element="../../api/system/login.php" />
                </Routes>
            </Router>
            <MobileHeader />
        </div>
    );
}

export default App;
