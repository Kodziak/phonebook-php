<template>
  <l-map
      :zoom="zoom"
      :center="center"
      :options="mapOptions"
      class="mx-auto w-1"
      style="height: 800px;"
      @update:center="centerUpdate"
      @update:zoom="zoomUpdate"
  >
    <l-tile-layer
        :url="url"
        :attribution="attribution"
    />

    <l-marker v-for="(address, key) in addressesArr" :key="key" :lat-lng="address.ll" >
      <l-popup>{{ `${address.name}, ${address.street}, ${address.postCode} ${address.city}` }}</l-popup>
    </l-marker>

  </l-map>
</template>

<script>
import { latLng, Icon } from 'leaflet';
import {
  LMap, LTileLayer, LMarker, LPopup,
} from '@vue-leaflet/vue-leaflet';
import 'leaflet/dist/leaflet.css';

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

export default {
  name: 'Map',
  props: ['longLatArr', 'addresses'],

  components: {
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
  },

  data() {
    return {
      zoom: 13,
      currentZoom: 11.5,
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a>',

      placesArr: [],
      // center: latLng(47.41322, -1.219482),

      center: latLng(47.41322, -1.219482),
      currentCenter: latLng(47.41322, -1.219482),

      mapOptions: {
        zoomSnap: 0.5,
      },
    };
  },

  created() {
    this.addressesArr = this.$props.addresses.map((map) => ({
      ...map,
      ll: latLng(map.latitude, map.longitude),
    }));

    this.center = this.addressesArr[0].ll;
    this.currentCenter = this.addressesArr[0].ll;
  },

  mounted() {
    this.center = this.addressesArr[0].ll;
    this.currentCenter = this.addressesArr[0].ll;
  },

  methods: {
    zoomUpdate(zoom) {
      this.currentZoom = zoom;
    },
    centerUpdate(center) {
      this.currentCenter = center;
    },
  },
};
</script>
