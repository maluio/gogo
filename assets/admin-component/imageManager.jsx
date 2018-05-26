import {Http} from "./http";

const {
    Button,
    Input
} = Reactstrap;

export class ImageManager extends React.Component {

    constructor() {
        super();
        this.state = {
            searchTerm: '',
            newImages: [],
            imageUrl: ''
        };

        this.searchImages = this.searchImages.bind(this);
        this.removeImage = this.removeImage.bind(this);
        this.handleImageUrlChanges = this.handleImageUrlChanges.bind(this);

    }

    searchImages(term) {
        Http.fetchImages(term).then((result) =>
            this.setState((prevState, props) => {
                return {
                    newImages: result,
                }
            })
        )
    }

    removeImage(imgToDelete){
        let images = this.props.images;
        images = images.filter((img) => img !== imgToDelete ? img : null);
        this.props.updateImages(images);
    }

    addImage(imgToAdd){
        let images = this.props.images;
        images.push(imgToAdd);
        this.props.updateImages(images);
        let newImages = this.state.newImages;
        newImages = newImages.filter(((img) => img !== imgToAdd ? img : null));
        this.setState({newImages: newImages});

    }

    renderImages(imagesToRender){
        let images = imagesToRender.map(
            (img, index) => {
                return (
                        <img
                            key={index}
                            className={"img-thumbnail existing-image"}
                            src={img.url}
                            onClick={()=>this.removeImage(img)}
                        />
                );
            }

        );
        return (
            <div>
                {images}
            </div>
        )
    }

    renderNewImages(imagesToRender){
        let images = imagesToRender.map(
            (img, index) => {
                return (
                        <img
                            key={index}
                            className={"img-thumbnail new-image"}
                            src={img.url}
                            onClick={()=>this.addImage(img)}
                        />
                );
            }

        );
        return (
            <div>
                {images}
            </div>
        )
    }

    handleImageUrlChanges(event){
        this.setState({imageUrl: event.target.value});
    }

    addImageUrl(url){
        if (!url) {
            return;
        }
        this.addImage({
            'url': url,
            'url_original': url,
            'url_thubmnail': url
            }
        )
    }

    renderAddImageUrl(){

        return (
            <div>
                <Input
                    type="text"
                    value={this.state.imageUrl}
                    onChange={this.handleImageUrlChanges}
                    placeholder="Add image url"
                />
                <Button
                    outline
                    block
                    color="success"
                    onClick={() => this.addImageUrl(this.state.imageUrl)}
                >Add image url</Button>
            </div>
        )
    }

    render() {
        return (
            <div id="imageManager" className="component">
                {this.renderNewImages(this.state.newImages)}
                <Button
                    outline
                    block
                    color="info"
                    onClick={() => this.searchImages(this.props.term)}
                >
                    Search images for "{this.props.term}"
                </Button>
                {this.renderAddImageUrl()}
                {this.renderImages(this.props.images)}
            </div>
        )
    }
}