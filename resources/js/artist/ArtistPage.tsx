import { useParams } from "react-router-dom";
import { Artist } from "./Artist";
import ArtistHeader from "./ArtistHeader";
import "./artist.css";
import AlbumsPage from "../album/AlbumsPage";
import ArtistTopSongs from "./ArtistTopSongs";
import { useQuery } from "react-query";
import axios from "axios";
import { Response } from "../App";

function ArtistPage() {
    const params = useParams();
    const artistID = params.artistID === undefined ? "" : params.artistID;
    const { isLoading, data, error } = useQuery("artist", () => axios.get<Response<Artist>>(`/api/artist/${artistID}`).then((response) => response.data));

    return (
        <>
            {error && <div>Failed to get artist</div>}
            {isLoading ? (<div>Is loading... </div>) :
                (<>
                    <ArtistHeader artist={data?.data} />
                    <div className="border-bottom border-white mt-5"></div>
                    {data?.data !== undefined &&
                        <>
                            <div className="mt-3">
                                <ArtistTopSongs artistID={artistID} />
                            </div>
                            <div className="border-bottom border-white mt-5"></div>
                            <div className="mt-5">
                                <AlbumsPage />
                            </div>
                        </>
                    }
                </>)
            }
        </>
    )
}

export default ArtistPage;
