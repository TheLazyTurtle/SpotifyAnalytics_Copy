import { Album } from "./Album";
import AlbumComponent from "./AlbumComponent";

interface AlbumListProps {
    albums?: Album[];
}

function AlbumList({ albums }: AlbumListProps) {
    return (
        <div>
            {albums?.map((album) => (
                <div key={album.id} className="cols-sm">
                    <AlbumComponent album={album} />
                </div>
            ))}
        </div>
    );
}

export default AlbumList;
