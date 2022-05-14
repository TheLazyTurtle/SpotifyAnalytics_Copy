import { Album } from "./Album";
import AlbumComponent from "./AlbumComponent";

interface AlbumListProps {
    albums: Album[];
}

function AlbumList({ albums }: AlbumListProps) {
    return (
        <div className="row">
            {albums.map((album) => (
                <div key={album.name} className="cols-sm">
                    <AlbumComponent album={album}/>
                </div>
            ))}
        </div>
    );
}

export default AlbumList;
