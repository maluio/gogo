export class HtmlRaw extends React.Component {

    createMarkup() {
        return {__html: this.props.raw};
    }

    render() {

        return (
            <div dangerouslySetInnerHTML={this.createMarkup()}/>
        )
    }
}