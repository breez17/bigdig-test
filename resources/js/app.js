import Resumable from 'resumablejs';

const progressEl = document.getElementById('upload__progress');

document.getElementById('upload__form').onsubmit = function (e) {
    e.preventDefault();

    const token = this._token.value;
    const entityID = this.entity_id.value;

    console.log(entityID);

    const resumable = new Resumable({
        chunkSize: 2 * 1024 * 1024,
        simultaneousUploads: 3,
        testChunks: false,
        throttleProgressCallbacks: 1,
        target: this.action,
        query: {
            _token : token,
            entity_id: entityID,
        }
    });

    resumable.addFiles(this.file.files);

    resumable.on('fileAdded', () => {
        resumable.upload();
    });

    resumable.on('fileProgress', () => {
        const percentProgress = Math.floor(resumable.progress() * 100);

        progressEl.innerText = `${percentProgress}/100`;
    });

    resumable.on('fileSuccess', (file, message) => {
        const { entityFile } = JSON.parse(message);

        const wrap = document.createElement('div');
        const link = document.createElement('a');

        link.href = entityFile.path;
        link.innerText = entityFile.name;
        link.target = '_blank';

        wrap.innerText = 'File path:';
        wrap.appendChild(link);

        progressEl.innerHTML = '';
        progressEl.appendChild(wrap);
    });
};
