import {
  ActivityIndicator,
  StyleSheet,
  View
} from "react-native"

const SplashScreen = () => {
  return (
    <View style={styles.container}>
      <ActivityIndicator size='large' color='#fff' />
    </View>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    borderColor: '#06bcee',
  },
});

export default SplashScreen;