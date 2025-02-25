
<!-- via https://github.com/bennoleslie/jsjpegmeta -->

<!DOCTYPE html>
<html id="dropbox" lang="en">

<head>
<meta charset="UTF-8">
<title>JsJpegMeta Demo</title>
<script type="text/javascript" src="jpegmeta.js"></script>
<script>

// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// LIB
// jpegmeta.js
// https://github.com/bennoleslie/jsjpegmeta/blob/master/jpegmeta.js
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------

/*
Copyright (c) 2009 Ben Leslie

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

/*
 This JavaScript library is used to parse meta-data from files
 with mime-type image/jpeg.

 Include it with something like:

   <script type="text/javascript" src="jpegmeta.js"...

 This adds a single 'module' object called 'JpegMeta' to the global
 namespace.

 Public Functions
 ----------------
 JpegMeta.parseNum - parse unsigned integers from binary data
 JpegMeta.parseSnum - parse signed integers from binary data

 Public Classes
 --------------
 JpegMeta.Rational - A rational number class
 JpegMeta.JfifSegment
 JpegMeta.ExifSegment
 JpegMeta.JpegFile - Primary class for Javascript parsing
*/

if (this.JpegMeta) {
    throw Error("Library included multiple times");
}

var JpegMeta = {};

JpegMeta.stringIsClean = function stringIsClean(str) {
    for (var i = 0; i < str.length; i++) {
  if (str.charCodeAt(i) < 0x20) {
      return false;
  }
    }
    return true;
}

/*
   parse an unsigned number of size bytes at offset in some binary string data.
   If endian
   is "<" parse the data as little endian, if endian
   is ">" parse as big-endian.
*/
JpegMeta.parseNum = function parseNum(endian, data, offset, size) {
    var i;
    var ret;
    var big_endian = (endian === ">");
    if (offset === undefined) offset = 0;
    if (size === undefined) size = data.length - offset;
    for (big_endian ? i = offset : i = offset + size - 1;
   big_endian ? i < offset + size : i >= offset;
   big_endian ? i++ : i--) {
  ret <<= 8;
  ret += data.charCodeAt(i);
    }
    return ret;
};

/*
   parse an signed number of size bytes at offset in some binary string data.
   If endian
   is "<" parse the data as little endian, if endian
   is ">" parse as big-endian.
*/
JpegMeta.parseSnum = function parseSnum(endian, data, offset, size) {
    var i;
    var ret;
    var neg;
    var big_endian = (endian === ">");
    if (offset === undefined) offset = 0;
    if (size === undefined) size = data.length - offset;
    for (big_endian ? i = offset : i = offset + size - 1;
   big_endian ? i < offset + size : i >= offset;
   big_endian ? i++ : i--) {
  if (neg === undefined) {
      /* Negative if top bit is set */
      neg = (data.charCodeAt(i) & 0x80) === 0x80;
  }
  ret <<= 8;
  /* If it is negative we invert the bits */
  ret += neg ? ~data.charCodeAt(i) & 0xff: data.charCodeAt(i);
    }
    if (neg) {
  /* If it is negative we do two's complement */
  ret += 1;
  ret *= -1;
    }
    return ret;
};

/* Rational number class */
JpegMeta.Rational = function Rational(num, den)
{
    this.num = num;
    this.den = den || 1;
    return this;
};

/* Rational number methods */
JpegMeta.Rational.prototype.toString = function toString() {
    if (this.num === 0) {
  return "" + this.num;
    }
    if (this.den === 1) {
  return "" + this.num;
    }
    if (this.num === 1) {
  return this.num + " / " + this.den;
    }
    return this.num / this.den; // + "/" + this.den;
};

JpegMeta.Rational.prototype.asFloat = function asFloat() {
    return this.num / this.den;
};


/* MetaGroup class */
JpegMeta.MetaGroup = function MetaGroup(fieldName, description) {
    this.fieldName = fieldName;
    this.description = description;
    this.metaProps = {};
    return this;
};

JpegMeta.MetaGroup.prototype._addProperty = function _addProperty(fieldName, description, value) {
    var property = new JpegMeta.MetaProp(fieldName, description, value);
    if (typeof this[property.fieldName] == 'undefined') {
        this[property.fieldName] = property;
        this.metaProps[property.fieldName] = property;
    } else {
        var currentProperty = this[property.fieldName];
        var currentMetaProperty = this.metaProps[property.fieldName];
        if (! Array.isArray(currentProperty)) {
            this[property.fieldName] = new Array();
            this[property.fieldName].push(currentProperty);
            this[property.fieldName].push(property);
            this.metaProps[property.fieldName] = new Array();
            this.metaProps[property.fieldName].push(currentProperty);
            this.metaProps[property.fieldName].push(property);
        } else {
            currentProperty.push(property);
            currentMetaProperty.push(property);
        }
    }
};

JpegMeta.MetaGroup.prototype.toString = function toString() {
    return "[MetaGroup " + this.description + "]";
};


/* MetaProp class */
JpegMeta.MetaProp = function MetaProp(fieldName, description, value) {
    this.fieldName = fieldName;
    this.description = description;
    this.value = value;
    return this;
};

JpegMeta.MetaProp.prototype.toString = function toString() {
    return "" + this.value;
};



/* JpegFile class */
this.JpegMeta.JpegFile = function JpegFile(binary_data, filename) {
    /* Change this to EOI if we want to parse. */
    var break_segment = this._SOS;

    this.metaGroups = {};
    this._binary_data = binary_data;
    this.filename = filename;

    /* Go through and parse. */
    var pos = 0;
    var pos_start_of_segment = 0;
    var delim;
    var mark;
    var _mark;
    var segsize;
    var headersize;
    var mark_code;
    var mark_fn;

    /* Check to see if this looks like a JPEG file */
    if (this._binary_data.slice(0, 2) !== this._SOI_MARKER) {
  throw new Error("Doesn't look like a JPEG file. First two bytes are " +
      this._binary_data.charCodeAt(0) + "," +
      this._binary_data.charCodeAt(1) + ".");
    }

    pos += 2;

    while (pos < this._binary_data.length) {
  delim = this._binary_data.charCodeAt(pos++);
  mark = this._binary_data.charCodeAt(pos++);

  pos_start_of_segment = pos;

  if (delim != this._DELIM) {
      break;
  }

  if (mark === break_segment) {
      break;
  }

  headersize = JpegMeta.parseNum(">", this._binary_data, pos, 2);

  /* Find the end */
  pos += headersize;
  while (pos < this._binary_data.length) {
      delim = this._binary_data.charCodeAt(pos++);
      if (delim == this._DELIM) {
    _mark = this._binary_data.charCodeAt(pos++);
    if (_mark != 0x0) {
        pos -= 2;
        break;
    }
      }
  }

  segsize = pos - pos_start_of_segment;

  if (this._markers[mark]) {
      mark_code = this._markers[mark][0];
      mark_fn = this._markers[mark][1];
  } else {
      mark_code = "UNKN";
      mark_fn = undefined;
  }

  if (mark_fn) {
      this[mark_fn](mark, pos_start_of_segment + 2, segsize - 2);
  }

    }

    if (this.general === undefined) {
  throw Error("Invalid JPEG file.");
    }

    return this;
};

this.JpegMeta.JpegFile.prototype.toString = function () {
    return "[JpegFile " + this.filename + " " +
  this.general.type + " " +
  this.general.pixelWidth + "x" +
  this.general.pixelHeight +
  " Depth: " + this.general.depth + "]";
};

/* Some useful constants */
this.JpegMeta.JpegFile.prototype._SOI_MARKER = '\xff\xd8';
this.JpegMeta.JpegFile.prototype._DELIM = 0xff;
this.JpegMeta.JpegFile.prototype._EOI = 0xd9;
this.JpegMeta.JpegFile.prototype._SOS = 0xda;

this.JpegMeta.JpegFile.prototype._sofHandler = function _sofHandler (mark, pos) {
    if (this.general !== undefined) {
  throw Error("Unexpected multiple-frame image");
    }

    this._addMetaGroup("general", "General");
    this.general._addProperty("depth", "Depth", JpegMeta.parseNum(">", this._binary_data, pos, 1));
    this.general._addProperty("pixelHeight", "Pixel Height", JpegMeta.parseNum(">", this._binary_data, pos + 1, 2));
    this.general._addProperty("pixelWidth", "Pixel Width",JpegMeta.parseNum(">", this._binary_data, pos + 3, 2));
    this.general._addProperty("type", "Type", this._markers[mark][2]);
};

this.JpegMeta.JpegFile.prototype._commentHandler = function _commentHandler (mark, pos, size) {

    var _pos, result;
    pos++;
    size--;
    _pos = pos;
    result = "";

    while(_pos < pos+size) {
        result += String.fromCharCode(this._binary_data.charCodeAt(_pos));
        _pos++;
    }

    this._addMetaGroup("comment", "Comment");
    this.comment._addProperty("comment", "Comment", result);
};


/* JFIF idents */
this.JpegMeta.JpegFile.prototype._JFIF_IDENT = "JFIF\x00";
this.JpegMeta.JpegFile.prototype._JFXX_IDENT = "JFXX\x00";

/* EXIF idents */
this.JpegMeta.JpegFile.prototype._EXIF_IDENT = "Exif\x00";

/* TIFF types */
this.JpegMeta.JpegFile.prototype._types = {
    /* The format is identifier : ["type name", type_size_in_bytes ] */
    1 : ["BYTE", 1],
    2 : ["ASCII", 1],
    3 : ["SHORT", 2],
    4 : ["LONG", 4],
    5 : ["RATIONAL", 8],
    6 : ["SBYTE", 1],
    7 : ["UNDEFINED", 1],
    8 : ["SSHORT", 2],
    9 : ["SLONG", 4],
    10 : ["SRATIONAL", 8],
    11 : ["FLOAT", 4],
    12 : ["DOUBLE", 8]
};

this.JpegMeta.JpegFile.prototype._tifftags = {
    /* A. Tags relating to image data structure */
    256 : ["Image width", "ImageWidth"],
    257 : ["Image height", "ImageLength"],
    258 : ["Number of bits per component", "BitsPerSample"],
    259 : ["Compression scheme", "Compression",
     {1 : "uncompressed", 6 : "JPEG compression" }],
    262 : ["Pixel composition", "PhotmetricInerpretation",
     {2 : "RGB", 6 : "YCbCr"}],
    274 : ["Orientation of image", "Orientation",
     /* FIXME: Check the mirror-image / reverse encoding and rotation */
     {1 : "Normal", 2 : "Reverse?",
      3 : "Upside-down", 4 : "Upside-down Reverse",
      5 : "90 degree CW", 6 : "90 degree CW reverse",
      7 : "90 degree CCW", 8 : "90 degree CCW reverse"}],
    277 : ["Number of components", "SamplesPerPixel"],
    284 : ["Image data arrangement", "PlanarConfiguration",
     {1 : "chunky format", 2 : "planar format"}],
    530 : ["Subsampling ratio of Y to C", "YCbCrSubSampling"],
    531 : ["Y and C positioning", "YCbCrPositioning",
     {1 : "centered", 2 : "co-sited"}],
    282 : ["X Resolution", "XResolution"],
    283 : ["Y Resolution", "YResolution"],
    296 : ["Resolution Unit", "ResolutionUnit",
     {2 : "inches", 3 : "centimeters"}],
    /* B. Tags realting to recording offset */
    273 : ["Image data location", "StripOffsets"],
    278 : ["Number of rows per strip", "RowsPerStrip"],
    279 : ["Bytes per compressed strip", "StripByteCounts"],
    513 : ["Offset to JPEG SOI", "JPEGInterchangeFormat"],
    514 : ["Bytes of JPEG Data", "JPEGInterchangeFormatLength"],
    /* C. Tags relating to image data characteristics */
    301 : ["Transfer function", "TransferFunction"],
    318 : ["White point chromaticity", "WhitePoint"],
    319 : ["Chromaticities of primaries", "PrimaryChromaticities"],
    529 : ["Color space transformation matrix coefficients", "YCbCrCoefficients"],
    532 : ["Pair of black and white reference values", "ReferenceBlackWhite"],
    /* D. Other tags */
    306 : ["Date and time", "DateTime"],
    270 : ["Image title", "ImageDescription"],
    271 : ["Make", "Make"],
    272 : ["Model", "Model"],
    305 : ["Software", "Software"],
    315 : ["Person who created the image", "Artist"],
    316 : ["Host Computer", "HostComputer"],
    33432 : ["Copyright holder", "Copyright"],

    34665 : ["Exif tag", "ExifIfdPointer"],
    34853 : ["GPS tag", "GPSInfoIfdPointer"]
};

this.JpegMeta.JpegFile.prototype._exiftags = {
    /* Tag Support Levels (2) - 0th IFX Exif Private Tags */
    /* A. Tags Relating to Version */
    36864 : ["Exif Version", "ExifVersion"],
    40960 : ["FlashPix Version", "FlashpixVersion"],

    /* B. Tag Relating to Image Data Characteristics */
    40961 : ["Color Space", "ColorSpace"],

    /* C. Tags Relating to Image Configuration */
    37121 : ["Meaning of each component", "ComponentsConfiguration"],
    37122 : ["Compressed Bits Per Pixel", "CompressedBitsPerPixel"],
    40962 : ["Pixel X Dimension", "PixelXDimension"],
    40963 : ["Pixel Y Dimension", "PixelYDimension"],

    /* D. Tags Relating to User Information */
    37500 : ["Manufacturer notes", "MakerNote"],
    37510 : ["User comments", "UserComment"],

    /* E. Tag Relating to Related File Information */
    40964 : ["Related audio file", "RelatedSoundFile"],

    /* F. Tags Relating to Date and Time */
    36867 : ["Date Time Original", "DateTimeOriginal"],
    36868 : ["Date Time Digitized", "DateTimeDigitized"],
    37520 : ["DateTime subseconds", "SubSecTime"],
    37521 : ["DateTimeOriginal subseconds", "SubSecTimeOriginal"],
    37522 : ["DateTimeDigitized subseconds", "SubSecTimeDigitized"],

    /* G. Tags Relating to Picture-Taking Conditions */
    33434 : ["Exposure time", "ExposureTime"],
    33437 : ["FNumber", "FNumber"],
    34850 : ["Exposure program", "ExposureProgram"],
    34852 : ["Spectral sensitivity", "SpectralSensitivity"],
    34855 : ["ISO Speed Ratings", "ISOSpeedRatings"],
    34856 : ["Optoelectric coefficient", "OECF"],
    37377 : ["Shutter Speed",  "ShutterSpeedValue"],
    37378 : ["Aperture Value", "ApertureValue"],
    37379 : ["Brightness", "BrightnessValue"],
    37380 : ["Exposure Bias Value", "ExposureBiasValue"],
    37381 : ["Max Aperture Value", "MaxApertureValue"],
    37382 : ["Subject Distance", "SubjectDistance"],
    37383 : ["Metering Mode", "MeteringMode"],
    37384 : ["Light Source", "LightSource"],
    37385 : ["Flash", "Flash"],
    37386 : ["Focal Length", "FocalLength"],
    37396 : ["Subject Area", "SubjectArea"],
    41483 : ["Flash Energy", "FlashEnergy"],
    41484 : ["Spatial Frequency Response", "SpatialFrequencyResponse"],
    41486 : ["Focal Plane X Resolution", "FocalPlaneXResolution"],
    41487 : ["Focal Plane Y Resolution", "FocalPlaneYResolution"],
    41488 : ["Focal Plane Resolution Unit", "FocalPlaneResolutionUnit"],
    41492 : ["Subject Location", "SubjectLocation"],
    41493 : ["Exposure Index", "ExposureIndex"],
    41495 : ["Sensing Method", "SensingMethod"],
    41728 : ["File Source", "FileSource"],
    41729 : ["Scene Type", "SceneType"],
    41730 : ["CFA Pattern", "CFAPattern"],
    41985 : ["Custom Rendered", "CustomRendered"],
    41986 : ["Exposure Mode", "Exposure Mode"],
    41987 : ["White Balance", "WhiteBalance"],
    41988 : ["Digital Zoom Ratio", "DigitalZoomRatio"],
    41989 : ["Focal length in 35 mm film", "FocalLengthIn35mmFilm"],
    41990 : ["Scene Capture Type", "SceneCaptureType"],
    41991 : ["Gain Control", "GainControl"],
    41992 : ["Contrast", "Contrast"],
    41993 : ["Saturation", "Saturation"],
    41994 : ["Sharpness", "Sharpness"],
    41995 : ["Device settings description", "DeviceSettingDescription"],
    41996 : ["Subject distance range", "SubjectDistanceRange"],

    /* H. Other Tags */
    42016 : ["Unique image ID", "ImageUniqueID"],

    40965 : ["Interoperability tag", "InteroperabilityIFDPointer"]
};

this.JpegMeta.JpegFile.prototype._gpstags = {
    /* A. Tags Relating to GPS */
    0 : ["GPS tag version", "GPSVersionID"],
    1 : ["North or South Latitude", "GPSLatitudeRef"],
    2 : ["Latitude", "GPSLatitude"],
    3 : ["East or West Longitude", "GPSLongitudeRef"],
    4 : ["Longitude", "GPSLongitude"],
    5 : ["Altitude reference", "GPSAltitudeRef"],
    6 : ["Altitude", "GPSAltitude"],
    7 : ["GPS time (atomic clock)", "GPSTimeStamp"],
    8 : ["GPS satellites usedd for measurement", "GPSSatellites"],
    9 : ["GPS receiver status", "GPSStatus"],
    10 : ["GPS mesaurement mode", "GPSMeasureMode"],
    11 : ["Measurement precision", "GPSDOP"],
    12 : ["Speed unit", "GPSSpeedRef"],
    13 : ["Speed of GPS receiver", "GPSSpeed"],
    14 : ["Reference for direction of movement", "GPSTrackRef"],
    15 : ["Direction of movement", "GPSTrack"],
    16 : ["Reference for direction of image", "GPSImgDirectionRef"],
    17 : ["Direction of image", "GPSImgDirection"],
    18 : ["Geodetic survey data used", "GPSMapDatum"],
    19 : ["Reference for latitude of destination", "GPSDestLatitudeRef"],
    20 : ["Latitude of destination", "GPSDestLatitude"],
    21 : ["Reference for longitude of destination", "GPSDestLongitudeRef"],
    22 : ["Longitude of destination", "GPSDestLongitude"],
    23 : ["Reference for bearing of destination", "GPSDestBearingRef"],
    24 : ["Bearing of destination", "GPSDestBearing"],
    25 : ["Reference for distance to destination", "GPSDestDistanceRef"],
    26 : ["Distance to destination", "GPSDestDistance"],
    27 : ["Name of GPS processing method", "GPSProcessingMethod"],
    28 : ["Name of GPS area", "GPSAreaInformation"],
    29 : ["GPS Date", "GPSDateStamp"],
    30 : ["GPS differential correction", "GPSDifferential"]
};

this.JpegMeta.JpegFile.prototype._iptctags = {
    0 : ['Record Version', 'recordVersion'],
    3 : ['Object Type Reference', 'objectType'],
    4 : ['Object Attribute Reference', 'objectAttribute'],
    5 : ['Object Name', 'objectName'],
    7 : ['Edit Status', 'editStatus'],
    8 : ['Editorial Update', 'editorialUpdate'],
    10 : ['Urgency', 'urgency'],
    12 : ['Subject Reference', 'subjectRef'],
    15 : ['Category', 'category'],
    20 : ['Supplemental Category', 'supplCategory'],
    22 : ['Fixture Identifier', 'fixtureID'],
    25 : ['Keywords', 'keywords'],
    26 : ['Content Location Code', 'contentLocCode'],
    27 : ['Content Location Name', 'contentLocName'],
    30 : ['Release Date', 'releaseDate'],
    35 : ['Release Time', 'releaseTime'],
    37 : ['Expiration Date', 'expirationDate'],
    38 : ['Expiration Time', 'expirationTime'],
    40 : ['Special Instructions', 'specialInstructions'],
    42 : ['Action Advised', 'actionAdvised'],
    45 : ['Reference Service', 'refService'],
    47 : ['Reference Date', 'refDate'],
    50 : ['Reference Number', 'refNumber'],
    55 : ['Date Created', 'dateCreated'],
    60 : ['Time Created', 'timeCreated'],
    62 : ['Digital Creation Date', 'digitalCreationDate'],
    63 : ['Digital Creation Time', 'digitalCreationTime'],
    65 : ['Originating Program', 'originatingProgram'],
    70 : ['Program Version', 'programVersion'],
    75 : ['Object Cycle', 'objectCycle'],
    80 : ['By-line', 'byline'],
    85 : ['By-line Title', 'bylineTitle'],
    90 : ['City', 'city'],
    92 : ['Sub-location', 'sublocation'],
    95 : ['Province/State', 'state'],
    100 : ['Country Code', 'countryCode'],
    101 : ['Country Name', 'countryName'],
    103 : ['Original Transmission Reference', 'origTransRef'],
    105 : ['Headline', 'headline'],
    110 : ['Credit', 'credit'],
    115 : ['Source', 'source'],
    116 : ['Copyright Notice', 'copyrightNotice'],
    118 : ['Contact', 'contact'],
    120 : ['Caption/Abstract', 'caption'],
    122 : ['Writer/Editor', 'writerEditor'],
    125 : ['Rasterized Caption', 'rasterizedCaption'],
    130 : ['Image Type', 'imageType'],
    131 : ['Image Orientation', 'imageOrientation'],
    135 : ['Language Identifier', 'languageID'],
    150 : ['Audio Type', 'audioType'],
    151 : ['Audio Sampling Rate', 'audioSamplingRate'],
    152 : ['Audio Sampling Resolution', 'audioSamplingRes'],
    153 : ['Audio Duration', 'audioDuration'],
    154 : ['Audio Outcue', 'audioOutcue'],
    200 : ['Preview File Format', 'previewFileFormat'],
    201 : ['Preview File Format Version', 'previewFileFormatVer'],
    202 : ['Preview Data', 'previewData']
};

this.JpegMeta.JpegFile.prototype._markers = {
    /* Start Of Frame markers, non-differential, Huffman coding */
    0xc0: ["SOF0", "_sofHandler", "Baseline DCT"],
    0xc1: ["SOF1", "_sofHandler", "Extended sequential DCT"],
    0xc2: ["SOF2", "_sofHandler", "Progressive DCT"],
    0xc3: ["SOF3", "_sofHandler", "Lossless (sequential)"],

    /* Start Of Frame markers, differential, Huffman coding */
    0xc5: ["SOF5", "_sofHandler", "Differential sequential DCT"],
    0xc6: ["SOF6", "_sofHandler", "Differential progressive DCT"],
    0xc7: ["SOF7", "_sofHandler", "Differential lossless (sequential)"],

    /* Start Of Frame markers, non-differential, arithmetic coding */
    0xc8: ["JPG", null, "Reserved for JPEG extensions"],
    0xc9: ["SOF9", "_sofHandler", "Extended sequential DCT"],
    0xca: ["SOF10", "_sofHandler", "Progressive DCT"],
    0xcb: ["SOF11", "_sofHandler", "Lossless (sequential)"],

    /* Start Of Frame markers, differential, arithmetic coding */
    0xcd: ["SOF13", "_sofHandler", "Differential sequential DCT"],
    0xce: ["SOF14", "_sofHandler", "Differential progressive DCT"],
    0xcf: ["SOF15", "_sofHandler", "Differential lossless (sequential)"],

    /* Huffman table specification */
    0xc4: ["DHT", null, "Define Huffman table(s)"],
    0xcc: ["DAC", null, "Define arithmetic coding conditioning(s)"],

    /* Restart interval termination" */
    0xd0: ["RST0", null, "Restart with modulo 8 count “0”"],
    0xd1: ["RST1", null, "Restart with modulo 8 count “1”"],
    0xd2: ["RST2", null, "Restart with modulo 8 count “2”"],
    0xd3: ["RST3", null, "Restart with modulo 8 count “3”"],
    0xd4: ["RST4", null, "Restart with modulo 8 count “4”"],
    0xd5: ["RST5", null, "Restart with modulo 8 count “5”"],
    0xd6: ["RST6", null, "Restart with modulo 8 count “6”"],
    0xd7: ["RST7", null, "Restart with modulo 8 count “7”"],

    /* Other markers */
    0xd8: ["SOI", null, "Start of image"],
    0xd9: ["EOI", null, "End of image"],
    0xda: ["SOS", null, "Start of scan"],
    0xdb: ["DQT", null, "Define quantization table(s)"],
    0xdc: ["DNL", null, "Define number of lines"],
    0xdd: ["DRI", null, "Define restart interval"],
    0xde: ["DHP", null, "Define hierarchical progression"],
    0xdf: ["EXP", null, "Expand reference component(s)"],
    0xe0: ["APP0", "_app0Handler", "Reserved for application segments"],
    0xe1: ["APP1", "_app1Handler"],
    0xe2: ["APP2", null],
    0xe3: ["APP3", null],
    0xe4: ["APP4", null],
    0xe5: ["APP5", null],
    0xe6: ["APP6", null],
    0xe7: ["APP7", null],
    0xe8: ["APP8", null],
    0xe9: ["APP9", null],
    0xea: ["APP10", null],
    0xeb: ["APP11", null],
    0xec: ["APP12", null],
    0xed: ["IPTC", "_iptcHandler", "IPTC Photo Metadata"],
    0xee: ["APP14", null],
    0xef: ["APP15", null],
    0xf0: ["JPG0", null], /* Reserved for JPEG extensions */
    0xf1: ["JPG1", null],
    0xf2: ["JPG2", null],
    0xf3: ["JPG3", null],
    0xf4: ["JPG4", null],
    0xf5: ["JPG5", null],
    0xf6: ["JPG6", null],
    0xf7: ["JPG7", null],
    0xf8: ["JPG8", null],
    0xf9: ["JPG9", null],
    0xfa: ["JPG10", null],
    0xfb: ["JPG11", null],
    0xfc: ["JPG12", null],
    0xfd: ["JPG13", null],
    0xfe: ["COM", "_commentHandler", "Comment"], /* Comment */

    /* Reserved markers */
    0x01: ["JPG13", null] /* For temporary private use in arithmetic coding */
    /* 02 -> bf are reserverd */
};

/* Private methods */
this.JpegMeta.JpegFile.prototype._addMetaGroup = function _addMetaGroup(name, description) {
    var group = new JpegMeta.MetaGroup(name, description);
    this[group.fieldName] = group;
    this.metaGroups[group.fieldName] = group;
    return group;
};

this.JpegMeta.JpegFile.prototype._parseIfd = function _parseIfd(endian, _binary_data, base, ifd_offset, tags, name, description) {
    var num_fields = JpegMeta.parseNum(endian, _binary_data, base + ifd_offset, 2);
    /* Per tag variables */
    var tag_base;
    var tag_field;
    var type, type_field, type_size;
    var num_values;
    var value_offset;
    var value;
    var _val;
    var num;
    var den;

    var group;

    group = this._addMetaGroup(name, description);

    for (var i = 0; i < num_fields; i++) {
  /* parse the field */
  tag_base = base + ifd_offset + 2 + (i * 12);
  tag_field = JpegMeta.parseNum(endian, _binary_data, tag_base, 2);
  type_field = JpegMeta.parseNum(endian, _binary_data, tag_base + 2, 2);
  num_values = JpegMeta.parseNum(endian, _binary_data, tag_base + 4, 4);
  value_offset = JpegMeta.parseNum(endian, _binary_data, tag_base + 8, 4);
  if (this._types[type_field] === undefined) {
      continue;
  }
  type = this._types[type_field][0];
  type_size = this._types[type_field][1];

  if (type_size * num_values <= 4) {
      /* Data is in-line */
      value_offset = tag_base + 8;
  } else {
      value_offset = base + value_offset;
  }

  /* Read the value */
  if (type == "UNDEFINED") {
      /* FIXME: This should be done better */
      /*value = _binary_data.slice(value_offset, value_offset + num_values); */
      value = undefined;
  } else if (type == "ASCII") {
      value = _binary_data.slice(value_offset, value_offset + num_values);
      value = value.split('\x00')[0];
      if (!JpegMeta.stringIsClean(value)) {
    value = "";
      }
      /* strip trail nul */
  } else {
      value = new Array();
      for (var j = 0; j < num_values; j++, value_offset += type_size) {
    if (type == "BYTE" || type == "SHORT" || type == "LONG") {
        value.push(JpegMeta.parseNum(endian, _binary_data, value_offset, type_size));
    }
    if (type == "SBYTE" || type == "SSHORT" || type == "SLONG") {
        value.push(JpegMeta.parseSnum(endian, _binary_data, value_offset, type_size));
    }
    if (type == "RATIONAL") {
        num = JpegMeta.parseNum(endian, _binary_data, value_offset, 4);
        den = JpegMeta.parseNum(endian, _binary_data, value_offset + 4, 4);
        value.push(new JpegMeta.Rational(num, den));
    }
    if (type == "SRATIONAL") {
        num = JpegMeta.parseSnum(endian, _binary_data, value_offset, 4);
        den = JpegMeta.parseSnum(endian, _binary_data, value_offset + 4, 4);
        value.push(new JpegMeta.Rational(num, den));
    }
    value.push();
      }
      if (num_values === 1) {
    value = value[0];
      }
  }
        if (tags.hasOwnProperty(tag_field)) {
      group._addProperty(tags[tag_field][1], tags[tag_field][0], value);
        } else {
            console.log("WARNING(jpegmeta.js): Unknown tag: ", tag_field);
        }
    }
};

this.JpegMeta.JpegFile.prototype._jfifHandler = function _jfifHandler(mark, pos) {
    if (this.jfif !== undefined) {
  throw Error("Multiple JFIF segments found");
    }
    this._addMetaGroup("jfif", "JFIF");
    this.jfif._addProperty("version_major", "Version Major", this._binary_data.charCodeAt(pos + 5));
    this.jfif._addProperty("version_minor", "Version Minor", this._binary_data.charCodeAt(pos + 6));
    this.jfif._addProperty("version", "JFIF Version", this.jfif.version_major.value + "." + this.jfif.version_minor.value);
    this.jfif._addProperty("units", "Density Unit", this._binary_data.charCodeAt(pos + 7));
    this.jfif._addProperty("Xdensity", "X density", JpegMeta.parseNum(">", this._binary_data, pos + 8, 2));
    this.jfif._addProperty("Ydensity", "Y Density", JpegMeta.parseNum(">", this._binary_data, pos + 10, 2));
    this.jfif._addProperty("Xthumbnail", "X Thumbnail", JpegMeta.parseNum(">", this._binary_data, pos + 12, 1));
    this.jfif._addProperty("Ythumbnail", "Y Thumbnail", JpegMeta.parseNum(">", this._binary_data, pos + 13, 1));
};


/* Handle app0 segments */
this.JpegMeta.JpegFile.prototype._app0Handler = function app0Handler(mark, pos) {
    var ident = this._binary_data.slice(pos, pos + 5);
    if (ident == this._JFIF_IDENT) {
  this._jfifHandler(mark, pos);
    } else if (ident == this._JFXX_IDENT) {
  /* Don't handle JFXX Ident yet */
    } else {
  /* Don't know about other idents */
    }
};


/* Handle app1 segments */
this.JpegMeta.JpegFile.prototype._app1Handler = function _app1Handler(mark, pos) {
    var ident = this._binary_data.slice(pos, pos + 5);
    if (ident == this._EXIF_IDENT) {
  this._exifHandler(mark, pos + 6);
    } else {
  /* Don't know about other idents */
    }
};

/* Handle exif segments */
JpegMeta.JpegFile.prototype._exifHandler = function _exifHandler(mark, pos) {
    if (this.exif !== undefined) {
  throw new Error("Multiple JFIF segments found");
    }

    /* Parse this TIFF header */
    var endian;
    var magic_field;
    var ifd_offset;
    var primary_ifd, exif_ifd, gps_ifd;
    var endian_field = this._binary_data.slice(pos, pos + 2);

    /* Trivia: This 'I' is for Intel, the 'M' is for Motorola */
    if (endian_field === "II") {
  endian = "<";
    } else if (endian_field === "MM") {
  endian = ">";
    } else {
  throw new Error("Malformed TIFF meta-data. Unknown endianess: " + endian_field);
    }

    magic_field = JpegMeta.parseNum(endian, this._binary_data, pos + 2, 2);

    if (magic_field !== 42) {
  throw new Error("Malformed TIFF meta-data. Bad magic: " + magic_field);
    }

    ifd_offset = JpegMeta.parseNum(endian, this._binary_data, pos + 4, 4);

    /* Parse 0th IFD */
    this._parseIfd(endian, this._binary_data, pos, ifd_offset, this._tifftags, "tiff", "TIFF");

    if (this.tiff.ExifIfdPointer) {
  this._parseIfd(endian, this._binary_data, pos, this.tiff.ExifIfdPointer.value, this._exiftags, "exif", "Exif");
    }

    if (this.tiff.GPSInfoIfdPointer) {
  this._parseIfd(endian, this._binary_data, pos, this.tiff.GPSInfoIfdPointer.value, this._gpstags, "gps", "GPS");
  if (this.gps.GPSLatitude) {
      var latitude;
      latitude = this.gps.GPSLatitude.value[0].asFloat() +
    (1 / 60) * this.gps.GPSLatitude.value[1].asFloat() +
    (1 / 3600) * this.gps.GPSLatitude.value[2].asFloat();
      if (this.gps.GPSLatitudeRef.value === "S") {
    latitude = -latitude;
      }
      this.gps._addProperty("latitude", "Dec. Latitude", latitude);
  }
  if (this.gps.GPSLongitude) {
      var longitude;
      longitude = this.gps.GPSLongitude.value[0].asFloat() +
    (1 / 60) * this.gps.GPSLongitude.value[1].asFloat() +
    (1 / 3600) * this.gps.GPSLongitude.value[2].asFloat();
      if (this.gps.GPSLongitudeRef.value === "W") {
    longitude = -longitude;
      }
      this.gps._addProperty("longitude", "Dec. Longitude", longitude);
  }
    }
};

this.JpegMeta.JpegFile.prototype._iptcHandler = function _iptcHandler(mark, pos, segsize) {
    this._addMetaGroup("iptc", "IPTC");

    var endian = '<';
    var offset, fieldStart, title, value, tag;
    var length = JpegMeta.parseNum(endian, this._binary_data, pos + 4, 1);
    var FILE_SEPARATOR_CHAR = 28,
        START_OF_TEXT_CHAR = 2;

    for (var i = 0; i < segsize; i++) {
        fieldStart = pos + i;
        if (JpegMeta.parseNum(endian, this._binary_data, fieldStart, 1) == START_OF_TEXT_CHAR) {
            tag = JpegMeta.parseNum(endian, this._binary_data, fieldStart + 1, 1);
            tag_desc = this._iptctags[tag];

            if (!tag_desc) continue;
            length = 0;
            offset = 2;

            while (
                offset < segsize &&
                JpegMeta.parseNum(endian, this._binary_data, fieldStart + offset, 1) != FILE_SEPARATOR_CHAR &&
                JpegMeta.parseNum(endian, this._binary_data, fieldStart + offset + 1, 1) != START_OF_TEXT_CHAR) {
                offset++;
                length++;
            }

            if (!length) continue;

            value = this._binary_data.slice(pos + i + 2, pos + i + 2 + length);
            value = value.replace('\000', '').trim();

            this.iptc._addProperty(tag_desc[1], tag_desc[0], value);
            i += length - 1;
        }
    }
};

// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// demo.js
// https://github.com/bennoleslie/jsjpegmeta/blob/master/demo.js
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
  
(function () {
  /* Imports */
  var $j = this.JpegMeta.JpegFile;

  /* Implementation */
  function $(x) {
    return document.getElementById(x);
  }

  function dragEnterHandler(e) {
    e.preventDefault();
  }

  function dragOverHandler(e) {
    e.preventDefault();
  }

  function dropHandler(e) {
    e.preventDefault();
    loadFiles(e.dataTransfer.files);
  }

  function strComp(a, b) {
    return (a > b) ? 1 : (a == b) ? 0 : -1;
  }

  function loadFiles(files) {
    var dataurl_reader = new FileReader();

    function display(data, filename) {
      var jpeg = new $j(data, filename);
      var groups = new Array;
      var props;
      var group;
      var prop;
      $("status").innerHTML += "JPEG File " + jpeg + "<br />";

      if (jpeg.gps && jpeg.gps.longitude) {
        $("status").innerHTML += "<a href='http://maps.google.com/?q=" + jpeg.gps.latitude + "," + jpeg.gps.longitude + "&amp;spn=0.05,0.05&amp;t=h&amp;om=1&amp;hl=en' target='_blank'>Locate on map</a> (opens a new window) <br />";
      }

      for (group in jpeg.metaGroups) {
        if (jpeg.metaGroups.hasOwnProperty(group)) {
          groups.push(jpeg.metaGroups[group]);
        }
      }

      groups.sort(function (a, b) {
        if (a.description == "General") {
            return -1;
        } else if (b.description == "General") {
            return 1;
        } else {
            return strComp(a.description, b.description);
        }
      });

      for (var i = 0; i < groups.length; i++) {
        group = groups[i];
        props = new Array();
        $("status").innerHTML += "<strong>" + group.description + "</strong><br />";
        for (prop in group.metaProps) {
                        if (group.metaProps.hasOwnProperty(prop)) {
                props.push(group.metaProps[prop]);
                        }
        }
        props.sort(function (a, b) { return strComp(a.description, b.description); });
        for (var j = 0; j < props.length; j++) {
                        prop = props[j];
            $("status").innerHTML += "<em>" + prop.description + ":</em> " + prop.value + "<br />";
        }
      }
    }

    dataurl_reader.onloadend = function() {
      $("img").src = this.result;
      display(atob(this.result.replace(/^.*?,/,'')), files[0]);
    }

    $("status").innerHTML = "";
    $("img").src = "";
    dataurl_reader.readAsDataURL(files[0]);
    $("form").reset();
  }

  window.onload = function() {
    var drop_el = $("dropbox");
    var file_el = $("fileWidget");
    drop_el.addEventListener("dragenter", dragEnterHandler, false);
    drop_el.addEventListener("dragover", dragOverHandler, true);
    drop_el.addEventListener("drop", dropHandler, true);
    file_el.addEventListener("change", function() { loadFiles(this.files); }, true);
  }

  /* No exports */

})();
</script>
</head>

<body>
  <h1>JsJpegMeta Demo</h1>
  <form id="form" action="javascript:void(0)">
    <p>Choose JPEG file: <input id="fileWidget" type="file"></p>
  </form>
  <img id="img" height="100" alt="Image preview" src="">
  <div id="status"></div>
</body>

</html>