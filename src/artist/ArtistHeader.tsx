import { Artist } from "./Artist";

interface HeaderProps {
    artist: Artist
};

function ArtistHeader({ artist }: HeaderProps) {
    return (
        <div className="container">
            <div key="info-wrapper" className="w-50 mx-auto">
                <div className="row small-row">
                    <div key="img-wrapper" className="col-md-3 mt-5">
                        <img className="artist-img" src={artist.img_url} />
                    </div>
                    <div key="text-wrapper" className="col-md-9 mt-5">
                        <h1 className="h-100">
                            <a className="text-white text-decoration-none align-text-bottom" href={artist.url} target="_blank">{artist.name}</a>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ArtistHeader;

