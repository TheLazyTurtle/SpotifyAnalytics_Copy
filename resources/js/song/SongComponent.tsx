import { Song } from "./Song";
import { Artist } from "../artist/Artist";

interface SongProps {
    song: Song;
}

function SongComponent({ song }: SongProps) {
    return (
        <section id={song.name} className="mt-3 mx-sm-3 mx-md-5">
            <div className="d-none d-md-inline-block">
                <p className="text-white text-center">{song.track_number}.</p>
            </div>
            <div className="col-xl-1 col-lg-2 d-inline-block align-middle mx-3">
                <img src={song.img_url} alt={song.name} width="80" />
            </div>
            <div className="col-xl-6 col-lg-3 d-inline-block align-middle">
                <a href={song.url} className="text-decoration-none">
                    <h6 className="text-white">{song.name}</h6>
                </a>
                {song.artists.map((artist: Artist) => (
                    <a key={artist.name} href={artist.url} className="text-decoration-none">{artist.name}, </a>
                ))}
            </div>
            <div className="col-2 d-xl-inline-block d-none align-middle">
                <audio src={song.preview_url} controls />
            </div>
        </section>
    );
}

export default SongComponent;

