import { useParams } from "react-router-dom";
import AlbumList from "./AlbumList";
import { Album } from "./Album";
import { useQuery } from "react-query";
import axios from "axios";
import { Response } from "../App";

interface AlbumPageProps {
    artistName?: string;
};

function AlbumsPage({ artistName }: AlbumPageProps) {
    const searchParams = useParams();
    const artistID = searchParams.artistID === undefined ? "" : searchParams.artistID;

    const params = {
        params: {
            artist_id: artistID
        }
    }
    const { isLoading, data, error } = useQuery("artistAlbums", () => axios.get<Response<Album[]>>(`/api/artist/albums`, params).then((response) => response.data));

    return (
        <>
            {error && (
                <div className="row">
                    <div className="card large error">
                        <section>
                            <p> <span className="icon-alert inverse "></span> Something died </p>
                        </section>
                    </div>
                </div>
            )}
            {isLoading ? (
                <div className="center-page">
                    <span className="spinner primary"></span>
                    <p>Loading...</p>
                </div>
            ) : (
                <AlbumList albums={data?.data} artistName={artistName} />
            )}
        </>

    );
}

export default AlbumsPage;
