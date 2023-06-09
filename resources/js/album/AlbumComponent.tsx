import { Album } from "./Album";
import SongComponent from "../song/SongComponent";
import "./album.css";
import "../index.css";
import { useState } from "react";

interface AlbumProps {
    album: Album;
    artistName?: string;
}

function AlbumComponent({ album, artistName }: AlbumProps) {
    const [isOpen, setIsOpen] = useState(false);

    function handleOnExpand() {
        setIsOpen(!isOpen);
    }

    return (
        <div className="col-lg-6 col-md-8 mx-md-auto mx-xs-0 mb-5 pb-2 album-wrapper" id={album.name}>
            <div className="album-header">
                <img src={album.imgUrl} alt={album.name} className="mx-md-5 my-md-3 album-md-img album-sm-img" />
                <section className="d-sm-block d-md-inline-block col-xs-2 mx-sm-3 my-sm-2 px-3 py-2 py-md-0 px-md-0">
                    <a href={album.url} className="text-decoration-none">
                        <h5 className="strong text-white">
                            <strong>{album.name}</strong>
                        </h5>
                    </a>
                    <a key={album.albumArtist?.name ?? artistName}
                        href={album.albumArtist?.url}
                        target="_blank"
                        rel="noreferrer"
                        className="text-decoration-none">
                        {album.albumArtist?.name ?? artistName}
                    </a>
                </section>
            </div>
            <div className={isOpen ? "d-block" : "d-none"}>
                <div className="album-body mt-3">
                    <section id="songs">
                        {isOpen && album.songs.map((song) => (
                            <SongComponent key={song.name} song={song} />
                        ))}
                    </section>
                </div>
                <div className="border-bottom border-white mt-5"></div>
            </div>
            <div key="album-folder">
                <p className="text-white text-center pt-2" onClick={handleOnExpand}>{isOpen ? "Close" : "Open"}</p>
            </div>
        </div>
    );
}

export default AlbumComponent;

