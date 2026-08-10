function doPost(e) {
  try {
    var data = JSON.parse(e.postData.contents);
    var blob = Utilities.newBlob(Utilities.base64Decode(data.fileData), data.mimeType, data.fileName);
    var file = DriveApp.createFile(blob);

    if (data.visibility === "public") {
      file.setSharing(DriveApp.Access.ANYONE_WITH_LINK, DriveApp.Permission.VIEW);
    } else {
      file.setSharing(DriveApp.Access.PRIVATE, DriveApp.Permission.VIEW);
    }

    var res = {
      "success": true,
      "file_id": file.getId(),
      "view_link": file.getUrl(),
      "download_link": "https://drive.google.com/uc?export=download&id=" + file.getId()
    };

    return ContentService.createTextOutput(JSON.stringify(res)).setMimeType(ContentService.MimeType.JSON);
  } catch (err) {
    var errRes = {
      "success": false,
      "error": String(err)
    };
    return ContentService.createTextOutput(JSON.stringify(errRes)).setMimeType(ContentService.MimeType.JSON);
  }
}
