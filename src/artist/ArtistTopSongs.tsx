import { useEffect, useState } from "react";
import { ArtistAPI } from "../api/ArtistAPI";

interface ArtistTopSongsProps {
    artistID: string
}

function ArtistTopSongs({ artistID }: ArtistTopSongsProps) {
    const [data, setData] = useState([]);
    const [open, setOpen] = useState<boolean>(false);

    useEffect(() => {
        getTopSongs(artistID);
    }, []);

    async function getTopSongs(artistID: string) {
        const artistResult = await ArtistAPI.topSongs(artistID);

        if (artistResult.success) {
            const data = artistResult.data;
            setData(data);
        } else {
            setData([]);
            console.log("We messed up. Very sad indeed");
        }
    }

    function handleShowMore() {
        const change = !open;
        setOpen(change);
    }

    return (
        <div className="container">
            <h2 className="text-center text-white">Top Songs</h2>
            <div className="mt-5">
                <div key="row-header" className="row small-row">
                    <div className="col-md-4 d-none d-md-block">
                        <p className="text-white text-center">Preview</p>
                    </div>
                    <div className="col-4 col-md-2">
                        <p className="text-white text-center">Image</p>
                    </div>
                    <div className="col-4 col-md-5">
                        <p className="text-white text-center">Title</p>
                    </div>
                    <div className="col-4 col-md-1">
                        <p className="text-white text-center">You / Total</p>
                    </div>
                </div>
                {data.map((data: any, index: number) => {
                    return (
                        <div key={index} className={index >= 4 ? "d-none" : "d-block"}>
                            <div className="row small-row py-1">
                                <div className="col-md-4 text-center d-none d-md-block">
                                    <audio src={data.preview_url} controls />
                                </div>
                                <div className="col-4 col-md-2 text-center">
                                    <img src={data.img_url} className="w-50" />
                                </div>
                                <div className="col-4 col-md-5">
                                    <p className="text-white text-center"><a href={data.url} className="text-decoration-none">{data.name}</a></p>
                                </div>
                                <div className="col-4 col-md-1 text-center">
                                    <p className="text-white">{data.user_count}/{data.count}</p>
                                </div>
                            </div>
                        </div>
                    )
                })}
            </div>
            <div key="show-more-btn" className="mt-5">
                <p className="text-center text-white" onClick={handleShowMore}>Show {open ? "less" : "more"}</p>
            </div>
        </div>
    );
}

export default ArtistTopSongs;
