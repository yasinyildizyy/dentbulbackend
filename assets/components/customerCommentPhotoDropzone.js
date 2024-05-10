import React, {useEffect, useState} from 'react';
import {useDropzone} from "react-dropzone";
import {ReactSortable} from "react-sortablejs";
import network from "../utils/network";

function CustomerCommentPhotoDropzone(props) {
  const [files, setFiles] = useState([]);
  useEffect(() => {
    if (props.customerCommentId) {
      getFiles().then();
      files.forEach(file => URL.revokeObjectURL(file.url));
    }
  }, []);
  const {getRootProps, getInputProps} = useDropzone({
    multiple: true,
    onDrop: acceptedFiles => {
      acceptedFiles.map(async (file, index) => {
        setTimeout(async() => {
          let formData = new FormData();
          formData.append('file', file);
          const response = await network.post(postUrl(), formData, true);
          if (201 === response.status) {
            setFiles(files => [response.data, ...files]);
            toastr.success("Fotoğraf yüklendi.");
          } else if (422 === response.status) {
            response?.data?.violations?.map(function (violation) {
              const message = violation?.message;
              if (message) {
                toastr.error(message);
              }
            })
          } else {
            toastr.error('Bir hata oluştu, lütfen başka bir görsel yüklemeyi deneyiniz.');
          }
        }, 1000 * (index + 1))
      });
    }
  });

  const postUrl = () => {
    return `/_api/admin/customer_comment_photos?customerCommentId=${props.customerCommentId}`;
  }

  const getFiles = async () => {
    const response = await network.getHydraMembers(
      true,
      `/_api/admin/customer_comment_photos`,
      {
        'customerComment': props.customerCommentId,
        'pagination': false
      }
    );
    setFiles(response);
  }

  const updateFilesSort = async () => {
    files.map((file, index) => {
      network.put(`/_api/admin/customer_comment_photos/${file.id}`, {
        'position': index
      }).then();
    })
    toastr.success("Fotoğrafların sırası güncellendi.");
  }

  const deleteFile = async (id) => {
    const response = await network.delete(`/_api/admin/customer_comment_photos/${id}`);
    if (204 === response.status) {
      document.getElementById('img-'+id).remove();
      toastr.success("Fotoğraf silindi.");
    }
  }

  return (
    <section className="container">
      <div {...getRootProps({className: 'dropzone dropzone-default dropzone-primary dz-clickable dz-started'})}>
        <input {...getInputProps()} />
        <strong>Sürükleyip bırakabilir yada tıklayıp yükleyebilirsiniz.</strong>
      </div>
      <ReactSortable
        expand={false}
        list={files}
        setList={setFiles}
      >
        {files.map(file => (
          <div id={'img-'+file.id} key={'img-'+file.id} className="image-input image-input-outline m-5">
            <div className="image-input-wrapper" style={{backgroundImage: `url(${file.url})`}}/>
            <span onClick={() => deleteFile(file.id)} className="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                  data-action="remove" data-toggle="tooltip" title="" data-original-title="Fotoğrafı Sil">
            <i className="ki ki-bold-close icon-xs text-muted"/>
          </span>
          </div>
        ))}
      </ReactSortable>
      <button className="btn btn-sm btn-primary float-right mt-3" onClick={updateFilesSort}>Sırayı Kaydet</button>
    </section>
  );
}

export default CustomerCommentPhotoDropzone;
