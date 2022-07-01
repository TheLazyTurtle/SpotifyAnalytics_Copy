import { Album } from "./Album";
import AlbumComponent from "./AlbumComponent";

interface AlbumListProps {
    albums?: Album[];
    artistName?: string;
}

function AlbumList({ albums, artistName }: AlbumListProps) {
    return (
        <div>
            {albums?.map((album) => (
                <div key={album.id} className="cols-sm">
                    <AlbumComponent album={album} artistName={artistName} />
                </div>
            ))}
        </div>
    );
}

export default AlbumList;
