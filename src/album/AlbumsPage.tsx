import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import AlbumList from "./AlbumList";
import { Album } from "./Album";
import { AlbumAPI } from "../api/AlbumAPI";

function AlbumsPage() {
    const [albums, setAlbums] = useState<Album[]>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | undefined>(undefined);
    // const [isOpen, setIsOpen] = useState(false);

    const params = useParams();
    const artistID = params.artistID === undefined ? "" : params.artistID;

    useEffect(() => {
        async function loadProjects() {
            setLoading(true);
            try {
                const data = await AlbumAPI.search(artistID);
                setError("");
                setAlbums(data);
            } catch (e) {
                if (e instanceof Error) {
                    setError(e.message);
                }
            } finally {
                setLoading(false);
            }
        }
        loadProjects();
    }, [artistID]);

    return (
        <>
            <h1>Projects</h1>
            {error && (
                <div className="row">
                    <div className="card large error">
                        <section>
                            <p> <span className="icon-alert inverse "></span> {error} </p>
                        </section>
                    </div>
                </div>
            )}
            <AlbumList albums={albums}/>
            {!loading && !error && (
                <div className="row">
                    <div className="col-sm-12">
                        <div className="button-group fluid">
                            <button className="button default">More...</button>
                            {/* <button className="button default" onClick={handleMoreClick}>More...</button> */}
                        </div>
                    </div>
                </div>
            )}
            {loading && (
                <div className="center-page">
                    <span className="spinner primary"></span>
                    <p>Loading...</p>
                </div>
            )}
        </>

    );
}

export default AlbumsPage;
