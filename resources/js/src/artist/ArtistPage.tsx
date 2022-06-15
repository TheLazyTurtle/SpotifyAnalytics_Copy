import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { ArtistAPI } from "../api/ArtistAPI";
import { Artist } from "./Artist";
import ArtistHeader from "./ArtistHeader";
import "./artist.css";
import AlbumsPage from "../album/AlbumsPage";
import ArtistTopSongs from "./ArtistTopSongs";

function ArtistPage() {
    const [artist, setArtist] = useState<Artist>({ artist_id: "", name: "", url: "", img_url: "" });

    const params = useParams();
    const artistID = params.artistID === undefined ? "" : params.artistID;

    useEffect(() => {
        getArtist(artistID);
        artist.artist_id = artistID;
        setArtist(artist);
    }, []);

    async function getArtist(artistID: string) {
        const artistResult = await ArtistAPI.getOne(artistID);

        if (artistResult.success) {
            const data = artistResult.data;

            const artist = {
                artist_id: data.artist_id,
                name: data.name,
                url: data.url,
                img_url: data.img_url
            }

            setArtist(artist);
        } else {
            console.log("We messed up. Very sad indeed");
        }
    }

    return (
        <>
            <ArtistHeader artist={artist} />
            <div className="border-bottom border-white mt-5"></div>
            <div className="mt-3">
                <ArtistTopSongs artistID={artistID} />
            </div>
            <div className="border-bottom border-white mt-5"></div>
            <div className="mt-5">
                <AlbumsPage />
            </div>
        </>
    )
}

export default ArtistPage;
