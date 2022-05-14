import { useState } from "react";
import { Album } from "./Album";
import SongComponent from "../song/SongComponent";
import "./album.css";

interface AlbumProps {
    album: Album;
}

function AlbumComponent({ album }: AlbumProps) {
    const [isOpen, setIsOpen] = useState(false);
    album.songs = album.songs.sort((a, b) => (a.trackNumber > b.trackNumber) ? 1 : -1);

    // TODO: collapse and expanding
    return (
        <div className="col-lg-6 col-md-8 mx-md-auto mx-xs-0 mb-5 pb-3 album-wrapper" id={album.name}>
            <div className="album-header">
                <img src={album.img} alt={album.name} className="mx-md-5 my-md-3 album-md-img album-sm-img"/>
                <section className="d-sm-block d-md-inline-block col-xs-2 mx-sm-3 my-sm-2">
                    <a href={album.url} className="text-decoration-none">
                        <h5 className="strong text-white">
                            <strong>{album.name}</strong>
                        </h5>
                    </a>
                    {album.albumArtists.map((artist) => {
                        if (artist.artistID === album.primaryArtistID) {
                            return <a key={artist.name} href={artist.url} className="text-decoration-none">{artist.name}</a>
                        }
                        return null;
                    })}
                </section>
            </div>
            <div className="album-body mt-3">
                <section id="songs">
                    {album.songs.map((song) => (
                        <SongComponent key={song.name} song={song}/>
                    ))}
                </section>
            </div>
        </div>
    );
}

export default AlbumComponent;

