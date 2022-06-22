import { useState } from "react";
import { useQuery } from "react-query";
import axios from "axios";
import { Response } from "../App";
import { Song } from "../song/Song";
import { Played } from "../graph/Played";

interface ArtistTopSongsProps {
    artistID: string
}

function ArtistTopSongs({ artistID }: ArtistTopSongsProps) {
    const params = {
        params: {
            artist_id: artistID,
        }
    }
    const { isLoading, data, error } = useQuery("artistTopSongs", () => axios.get<Response<Played<Song>[]>>(`/api/artist/topSongs`, params).then((response) => response.data));
    const [open, setOpen] = useState<boolean>(false);

    function handleShowMore() {
        setOpen(!open);
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
                {!isLoading && data?.data.map((dataWrapper, index: number) => {
                    return (
                        <div key={index} className={(!open && index >= 5) ? "d-none" : "d-block"}>
                            <div className="row small-row py-1">
                                <div className="col-md-4 text-center d-none d-md-block">
                                    <audio src={dataWrapper.object?.previewUrl} controls />
                                </div>
                                <div className="col-4 col-md-2 text-center">
                                    <img src={dataWrapper.object?.imgUrl} className="w-50" alt="song cover" />
                                </div>
                                <div className="col-4 col-md-5">
                                    <p className="text-white text-center"><a href={dataWrapper.object?.url} className="text-decoration-none">{dataWrapper.object?.name}</a></p>
                                </div>
                                <div className="col-4 col-md-1 text-center">
                                    <p className="text-white">{dataWrapper.x}/{dataWrapper.y}</p>
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
