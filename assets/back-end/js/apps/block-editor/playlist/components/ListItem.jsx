const { __ } = wp.i18n,
    { dateI18n } = wp.date,
    { Button } = wp.components,
    { noImageUrl } = vmtq;

class ListItem extends React.Component{
    /**
     *
     * @param props
     */
    constructor( props ) {
        super( props );
        this.post = this.props.post;
    }

    getImageUrl(){
        return undefined !== this.post.vimeo_video.thumbnail ?
                this.post.vimeo_video.thumbnail :
                noImageUrl
    }

    render(){

        return (
            <div
                className={`col-xs-6  col-sm-4 col-md-3 col-lg-2 grid-element ${this.props.className}`}
            >
                <div
                    className='cvm-video'
                >
                    <div className='cvm-thumbnail'>
                        {
                            this.post.vimeo_video != null &&
                            <div>
                                <img
                                    src={ this.getImageUrl() }
                                />
                                <span className='duration'>{ this.post.vimeo_video._duration }</span>
                            </div>
                        }
                    </div>
                    <div className='details'>
                        <h4>{ jQuery('<textarea />').html( this.post.title.rendered ).text() }</h4>

                        <div className="meta">
                            <span className="publish-date">{ dateI18n( 'M d Y', this.post.date ) }</span>
                        </div>

                        <div className='actions'>
                            <Button
                                className='select-button'
                                isLink
                                onClick={
                                    () => {
                                        this.props.onSelect( this.post )
                                    }
                                }
                            >
                                { this.props.selectText }
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

ListItem.defaultProps = {
    post: null, // post object returned by WP REST Api
    selectText : 'Select',
    onSelect: ( post )=>{}, // click event when selected
    className: ''
}

export default ListItem