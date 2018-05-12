/* routes */
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

export class ImageManager extends React.Component {

    constructor() {
        super();
        this.state = {
            searchTerm: '',
            newImages: [],
            imageUrl: ''
        };

        this.handleSearchChange = this.handleSearchChange.bind(this);
        this.searchImages = this.searchImages.bind(this);
        this.removeImage = this.removeImage.bind(this);
        this.handleImageUrlChanges = this.handleImageUrlChanges.bind(this);

    }

    searchImages(term) {
        fetch(
            Routing.generate('search_images', {term: term}),
            {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState((prevState, props) => {
                        return {
                            newImages: result,
                        }
                    });
                },
                (error) => {
                }
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

    handleSearchChange(event) {
        this.setState({searchTerm: event.target.value});
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
            <div className="form-group form-inline">
                <input
                    className="form-control"
                    value={this.state.imageUrl}
                    onChange={this.handleImageUrlChanges}
                    type="text"
                    placeholder="Add image url"
                />
                <button
                    onClick={() => this.addImageUrl(this.state.imageUrl)}
                    className="form-control"
                >Past and add image url</button>
            </div>
        )
    }

    render() {
        return (
            <div id="imageManager">
                {this.renderAddImageUrl()}
                {this.renderNewImages(this.state.newImages)}
                <div className="form-group form-inline">
                    <input
                        className="form-control"
                        value={this.state.searchTerm}
                        onChange={this.handleSearchChange}
                        type="text"
                        placeholder="Search images"
                    />
                    <button
                        onClick={() => this.searchImages(this.state.searchTerm)}
                        className="form-control"
                    >
                        <span className="oi oi-question-mark"></span>
                    </button>
                </div>
                {this.renderImages(this.props.images)}
            </div>
        )
    }
}