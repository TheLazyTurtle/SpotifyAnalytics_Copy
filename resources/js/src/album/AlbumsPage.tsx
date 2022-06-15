import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import AlbumList from "./AlbumList";
import { Album } from "./Album";
import { ArtistAPI } from "../api/ArtistAPI";

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
                const data = await ArtistAPI.albums(artistID);
                if (data.success) {
                    setError("");
                    setAlbums(data.data);
                } else {
                    setError("We pepsi max boi");
                    setAlbums([]);
                }
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
            {error && (
                <div className="row">
                    <div className="card large error">
                        <section>
                            <p> <span className="icon-alert inverse "></span> {error} </p>
                        </section>
                    </div>
                </div>
            )}
            <AlbumList albums={albums} />
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
