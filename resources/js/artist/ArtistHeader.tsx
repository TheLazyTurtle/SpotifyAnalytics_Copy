import { Artist } from "./Artist";

interface HeaderProps {
    artist: Artist
};

function ArtistHeader({ artist }: HeaderProps) {
    return (
        <div className="container p-0 px-md-5">
            <div key="info-wrapper" className="w-100 w-md-50 mx-auto">
                <div className="row small-row">
                    <div key="img-wrapper" className="col-md-3 p-0 mt-md-5">
                        <img className="artist-img" src={artist.img_url} />
                    </div>
                    <div key="text-wrapper" className="col-md-9 mt-3 mt-md-5">
                        <h1 className="h-100">
                            <a className="text-white text-decoration-none align-text-bottom px-2 px-md-0" href={artist.url} target="_blank">{artist.name}</a>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ArtistHeader;

